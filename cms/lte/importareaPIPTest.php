<?php
include '../shared/header.php';
include '../shared/base_station.php';
include '../shared/pointLocation.php';

// Point-In-Polygon Test
/*$pointLocation = new pointLocation();
$points = array("50 70","70 40","-20 30","100 10","-10 -10","40 -20","110 -20");
$polygon = array("-50 30","50 70","100 50","80 10","110 -10","110 -30","-20 -50","-30 -40","10 -10","-10 10","-30 -20","-50 30");
// The last point's coordinates must be the same as the first one's, to "close the loop"
foreach($points as $key => $point) {
    echo "point " . ($key+1) . " ($point): " . $pointLocation->pointInPolygon($point, $polygon, true) . "<br>";
}*/

class importLTEAreas {

    var $failCount = 0;
    var $successCount = 0;
    var $polygoncount = 0;
    var $areas = "";
    // Point-In-Polygon Variables
    var $pointLocation;
    var $approvedPolygons = array();
    var $previousPolygon = array();
    var $currentPolygon = array();


    //Import from files in an import folder
    function doImport() {        
        $this->pointLocation = new pointLocation();
        if ($arr = scandir('../../polygons/importsource/lte/')) {
            foreach ($arr as $entry) {
                if (strlen($entry) > 4) {
                    // Create polyline
                            
                        
                    // -- load bases from xml
                    $xml = simplexml_load_file("../../polygons/importsource/lte/$entry");
                    echo "filename: ".$entry."<br/>";
                    $multi = $xml->Document->Placemark->MultiGeometry->children();
                    foreach ($multi as $polygon) {
                        if(!empty($polygon->altitudeMode)){
                            $this->polygoncount++;
                            $altitudeMode = $polygon->altitudeMode;
                            $coordinates = $polygon->outerBoundaryIs->LinearRing->coordinates;

                            // create polygon file
                            $this->areas = $this->getPolygonData($altitudeMode, $coordinates, $this->polygoncount);
                            if($this->areas != "") {
                                $successResults = $this->createPolygonFile($this->areas, $this->polygoncount);
                            }
                        }    
                    }
                }
            }
            echo "approvedPolygons: ";
            print_r($this->approvedPolygons);
            echo "<br/><br/>";
        }
    }

    function getPolygonData($_altitudeMode, $_coordinates, $_count) {
        $this->polygoncount = $_count;
        $this->currentPolygon = array();
        $altitudeMode = $_altitudeMode;
        $coordinates = explode(",0", $_coordinates);
        $results = "[<br/>";
        
        // -- 
        $roundto = 4;
        foreach ($coordinates as $acoordinate) {    
            if(!empty($acoordinate)){
                $longitudeLatitude = explode(",", $acoordinate);
                $shortlongitude = (isset($longitudeLatitude[1])) ? round($longitudeLatitude[1], $roundto) : 0;
                $shortlatitude = (isset($longitudeLatitude[0])) ? round($longitudeLatitude[0], $roundto) : 0;
                if($shortlongitude != 0 && $shortlatitude != 0) {

                    // push co-ords to the end of the currentPolygon array
                    array_push($this->currentPolygon, $shortlongitude." ".$shortlatitude);

                    $results .="new google.maps.LatLng(".$shortlongitude.",".$shortlatitude."),<br/>";
                }
            }
        }  
        /* ***
        * At this point, we have:-
        * 1. "currentPolygon" containing all non-zero co-ords 
        * 2. the first part of "$results" string, containing a set of "google.maps.LatLng" points
        * ***/


        /*** 
         * If "currentPolygon" contains no array values, there's no need to return "$results" string values 
         * (i.e. No need to create a new php file later containing no "google.maps.LatLng" points).
         * So we set "$results" to an empty string, which will mean "createPolygonFile" won't run later on.
         * **/
        if(empty($this->currentPolygon)) {$results = "";}


        else {    

            $outside = false;
            // if approvedPolygons array is empty add currentPolygon to it
            if(empty($this->approvedPolygons)){
                array_push($this->approvedPolygons, $this->currentPolygon);
            } else {
                foreach($this->approvedPolygons as $approvedPolygon) {
                    // look through all other approvedPolygons...
                    // and check all points of currentPolygon are in any of them
                    foreach($this->currentPolygon as $key => $point) {
                        $outside = ($this->pointLocation->pointInPolygon($point, $approvedPolygon, false) == "outside") ? true : false;
                        if($outside) {
                            array_push($this->approvedPolygons, $this->currentPolygon);
                            //echo "point " . ($key+1) . " ($point): is outside polygon.<br>";
                            break;
                        }
                    }
                    echo "<br/>";                        
                    if($outside) {
                        break;
                    }
                }
            }
            if($outside){
                $results .= "],code:'',zone:'',tech:'lteAreas', title:'Zimbabwe'";
                $results = str_replace(",<br/>]","<br/>]",$results);
            } else {
                $results = "";
            }
        }

        return $results;
    } 

    function createPolygonFile($_areas, $_count) {
        $count = $_count;
        $this->areas = $_areas;
        $phpFileName = "LteZoomArea".$count.".php";
        $fopenMode = "w";
        $phpFileHandle = fopen("../../polygons/lte/".$phpFileName, $fopenMode);
        
        if($phpFileHandle){
            //echo "phpFileHandle: ".$phpFileHandle."<br/><br/>";
            
            $this->areas = str_replace("<br/>","\n",$this->areas);
            $this->areas = "<?php\n\necho \"".$this->areas."\";";
            
            $fwriteResult = fwrite($phpFileHandle, $this->areas);
            if($fwriteResult){
                $this->successCount++;
                //echo "Success: ".$fwriteResult."<br/>";
            } else {
                $this->failCount++;
                echo "Error writing to: ".$phpFileName."<br/>";
            }
            fclose($phpFileHandle);
        } else {
            echo "Error locating/opening: ".$phpFileName."<br/>";
        }
    } 
    
    function postData($_name, $_latitude, $_longitude, $_tilt, $_range, $_notes) {
        $name = $_name;
        $latitude = $_latitude;
        $longitude = $_longitude;
        $tilt = $_tilt;
        $range = $_range;
        $notes = $_notes;
        
        $isInFibroniksArea =  0;
        $decomissioned =  0;
            
        
        $dbUtils = new DbUtils ();
        $res = $dbUtils->InsertUpdateQuery ( "INSERT INTO base_stations (`Name`, `Latitude`, `Longitude`, `Tilt`, `Range`, `IsInFibroniksArea`, `Decomissioned`, `Notes`)
                VALUES ('$name', '$latitude', '$longitude', $tilt, $range, $isInFibroniksArea, $decomissioned, '$notes')" );
        
        
        
        //check if saved 
        if ($res > 0) {
            $this->successCount++;		
        } else{
            $this->failCount++;
        }
    } 
}
$importLTEAreas = new importLTEAreas();
$importLTEAreas->doImport();

?>       
<div class="right_col" role="main">
	<h3>Import base stations</h3>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<p>Have successfull added <?= $importLTEAreas->successCount; ?> LTE ZOOM Zones</p>
					<p>Have failed to add <?= $importLTEAreas->failCount; ?> LTE ZOOM Zones</p>
				</div>
			</div>
		</div>
	</div>  
</div>
<?php  include '../shared/footer.php'; ?>