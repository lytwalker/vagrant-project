<?php
include '../shared/header.php';
include '../shared/base_station.php';

$failCount = 0;
$successCount = 0;
$polygoncount = 0;
$areas = "";

//Import from files in an import folder
if ($arr = scandir('../../polygons/importsource/lte/'))
    foreach ($arr as $entry)
        if (strlen($entry) > 4) {
            // Create polyline
                      
                   
            // -- load bases from xml
            $xml = simplexml_load_file("../../polygons/importsource/lte/$entry");
            $multi = $xml->Document->Placemark->MultiGeometry->children();
            foreach ($multi as $polygon) {
                if(!empty($polygon->altitudeMode)){
                    $polygoncount++;
                    $altitudeMode = $polygon->altitudeMode;
                    $coordinates = $polygon->outerBoundaryIs->LinearRing->coordinates;

                    // create polygon file
                    $areas = getPolygonData($altitudeMode, $coordinates, $polygoncount);
                    //echo "areas: ".$areas;
                    if($areas != "") {
                        $successResults = createPolygonFile($areas, $polygoncount);
                    }

                    // -- successes and failures
                    $successCount = $successCount + $successResults[0];
                    $failCount = $failCount + $successResults[1];
                }    
            }
        }


function getPolygonData($_altitudeMode, $_coordinates, $_count) {
	$polygoncount = $_count;
	$altitudeMode = $_altitudeMode;
	$coordinates = explode(",0", $_coordinates);
    $results = "[<br/>";
    
    // -- 
    $roundto = 4;
    $added = 0;
    //$nthcoord = 3;
    foreach ($coordinates as $acoordinate) {    
        if(!empty($acoordinate)){
            //if(($nthcoord--) <= 0) { 
            //    $nthcoord = 3;
                $longitudeLatitude = explode(",", $acoordinate);
                $shortlongitude = (isset($longitudeLatitude[1])) ? round($longitudeLatitude[1], $roundto) : 0;
                $shortlatitude = (isset($longitudeLatitude[0])) ? round($longitudeLatitude[0], $roundto) : 0;
                if($shortlongitude != 0 && $shortlatitude != 0) {
                    $results .="new google.maps.LatLng(".$shortlongitude.",".$shortlatitude."),<br/>";
                    $added++;
                }
            //}
        }
    }  
    //$results .= "],code:'',zone:'',tech:'lteAreas', title:'LTE Area: ".$polygoncount."'";  
    $results .= "],code:'',zone:'',tech:'lteAreas', title:'Zimbabwe'";
    $results = str_replace(",<br/>]","<br/>]",$results);
    
    // set to empty string if no co-ords were added to $results
    if($added == 0) {$results = "";}

    return $results;
} 

function createPolygonFile($_areas, $_count) {
	$count = $_count;
	$areas = $_areas;
    $phpFileName = "LteZoomArea".$count.".php";
    $fopenMode = "w";
    $phpFileHandle = fopen("../../polygons/lte/".$phpFileName, $fopenMode);
    $successAndFail = [0,0];
    
    if($phpFileHandle){
        //echo "phpFileHandle: ".$phpFileHandle."<br/><br/>";
        
        $areas = str_replace("<br/>","\n",$areas);
        $areas = "<?php\n\necho \"".$areas."\";";
        
        $fwriteResult = fwrite($phpFileHandle, $areas);
        if($fwriteResult){
            $successAndFail[0]++;
            //echo "Success: ".$fwriteResult."<br/>";
        } else {
            $successAndFail[1]++;
            echo "Error writing to: ".$phpFileName."<br/>";
        }
        fclose($phpFileHandle);
    } else {
        echo "Error locating/opening: ".$phpFileName."<br/>";
    }
    return $successAndFail;
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
		$GLOBALS['successCount']++;		
	} else{
		$GLOBALS['failCount']++;
	}
} 

?>       
<div class="right_col" role="main">
	<h3>Import base stations</h3>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<p>Have successfull added <?= $successCount; ?> LTE ZOOM Zones</p>
					<p>Have failed to add <?= $failCount; ?> LTE ZOOM Zones</p>
				</div>
			</div>
		</div>
	</div>  
</div>
<?php  include '../shared/footer.php'; ?>