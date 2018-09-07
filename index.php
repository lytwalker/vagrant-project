<?php

// reference
// https://developers.google.com/maps/documentation/javascript/reference
// Require and Includes
require_once 'header.php';
require_once 'code/ZOLPaymentsUtils.php';
include 'cms/shared/base_station.php';
include 'cms/shared/helper.php';

//test 
// Build Content
$zpu = new ZOLPaymentsUtils();
// Get posted fields from step1
$area = isset($_POST['area']) ? $_POST['area'] : '';
?>
<script>
    // Variables
    var map = {};
    var gponLines = {};
    var gponAreas = {};
    var wimaxBases = {};
    var fiberAreas = {};
    var lteBases = {};
    var lteAreas = {};
    var basePosition = new google.maps.LatLng(-17.79716, 31.04514);
    var canLocate = true;
    var baseVisibility = false;
    var gponVisibility = false;
    var wiMaxVisibility = false;
    var fiberVisibility = false;
    var lteVisibility = false;
    var lteAreasVisibility = false;
    var lteAreasWasVisibility = false;
    jQuery(document).ready(function () {
        // Google Maps for Street name
        jQuery("#search").geocomplete({details: "form", country: 'zw'});
        jQuery("#search").on('keyup', function (event) {
            if (event.keyCode == 13) {
                jQuery("#search").trigger("geocode");
                setTimeout(search, 100);
            }
        });
        jQuery("#btnSearch").on('click', function (event) {
            jQuery("#search").trigger("geocode");
            setTimeout(search, 100);
        });
        initialize();
    });
    function search() {
        try {
            if (jQuery('input[name=lat]').val() == 0 && jQuery('input[name=lng]').val() == 0) {
                setTimeout(search, 100);
                jQuery('#loading').show();
                return;
            }
            var latLng = new google.maps.LatLng(jQuery('input[name=lat]').val(), jQuery('input[name=lng]').val());
            getInfo(latLng);
            map.setZoom(15);
            map.setCenter(latLng);
        } catch (e) {
            alert(e);
        }
        jQuery('#loading').hide();
    }
<?php
if (isset($_GET['latLng'])) {
    $zoom = 15;
    $latLng = $zpu->HashDecode($_GET['latLng']);
    echo ""
    . "basePosition = new google.maps.LatLng($latLng); "
    . "canLocate = false; ";
} else {
    $zoom = 12;
}
?>
    var baseMarker = new google.maps.Marker({position: basePosition, draggable: true});
    // Functions
    function initialize() {        
        map = new google.maps.Map(document.getElementById('map-canvas'), {
			center: basePosition, country: 'zw',
			scrollwheel: false,
			mapTypeControlOptions: {
				  style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				  position: google.maps.ControlPosition.TOP_RIGHT
			  },
			zoomControlOptions: {
				  position: google.maps.ControlPosition.RIGHT_TOP
			  },
			streetViewControl: false,
			streetViewControlOptions: {
				  position: google.maps.ControlPosition.RIGHT_TOP
			  }
			});
        map.setZoom(<?= $zoom ?>);
        baseMarker.setMap(map);

<?php
// Do base stations
include "polygons/wimax-bases.php";
/*include "polygons/lte-decomssioned-base-stations.php";
include "polygons/lte-base-stations.php";
include "polygons/lte-bases.php";*/
include "polygons/lte-bases-and-polygons.php";

// Do polygons and polylines
if ($arr = scandir('polygons/gpon'))
    foreach ($arr as $entry)
        if (strlen($entry) > 4) {
            // Create polyline
            echo "gponLines['$entry'] = {shape:new google.maps.Polyline({path: ";
            include "polygons/gpon/$entry";
            echo",";
            include "polygons/gpon_paint/opts-polyline.php";
            echo"})};";
            // Create Polygons 
            echo "gponAreas['$entry'] = {shape:new google.maps.Polygon({path: ";
            include "polygons/gpon/$entry";
            echo",";
            include "polygons/gpon_paint/opts-polygon.php";
            echo"})};";
        }

//Do Fibre
if ($arr = scandir('polygons/fiber'))
    foreach ($arr as $entry)
        if (strlen($entry) > 4) {
            // Create polyline
            echo "fiberAreas['$entry'] = {shape:new google.maps.Polyline({path: ";
            include "polygons/fiber/$entry";
            echo",";
            include "polygons/fiber_paint/opts-polyline.php";
            echo"})};";
            // Create Polygons 
            echo "fiberAreas['$entry'] = {shape:new google.maps.Polygon({path: ";
            include "polygons/fiber/$entry";
            echo",";
            include "polygons/fiber_paint/opts-polygon.php";
            echo"})};";
        }

?>
        baseMarker.setPosition(basePosition);
        map.setCenter(basePosition);
        // Pin current user location
        if (canLocate == false) {
            getInfo(basePosition);
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                basePosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                baseMarker.setPosition(basePosition);
                map.setCenter(basePosition);
            }, function () {
                // There was an error
            });
        } else {
            // Browser doesn't support Geolocation
        }

        // Add listener for drawing polygons
        google.maps.event.addListener(baseMarker, 'dragend', function () {
            getInfo(baseMarker.getPosition());
        });
        google.maps.event.addListener(map, 'click', function (e) {
            getInfo(e.latLng);
        });
        for (var x in gponAreas) {
            google.maps.event.addListener(gponAreas[x].shape, 'click', function (e) {
                getInfo(e.latLng);
            });
        }
        
        for (var x in fiberAreas) {
            google.maps.event.addListener(fiberAreas[x].shape, 'click', function (e) {
                getInfo(e.latLng);
            });
        }
        
        for (var x in lteBases) {
            google.maps.event.addListener(lteBases[x].shape, 'click', function (e) {
                getInfo(e.latLng);
            });
        }
        
        for (var x in lteAreas) {
            google.maps.event.addListener(lteAreas[x].shape, 'click', function (e) {
                getInfo(e.latLng);
            });
        }

        // When the user clicks, set 'isColorful', changing the color of the letters.
        map.data.addListener('click', function(e) {
            getInfo(e.latLng);
        });
        
        // Add custom controls
        // Create the DIV to hold the control and
        // call the PolygonsControl() constructor passing
        // in this DIV.
        var centerControlDiv = document.createElement('div');
        centerControlDiv.id = "coverageBtns";
        var centerControl = new CenterControl(centerControlDiv, map);
        centerControlDiv.index = 0;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(centerControlDiv);
    }
    function MakeVisible(visible){
        
        if(visible) {
        if(lteAreasWasVisibility) {
            SetDefaultMapStyle();
        } else {
            <?php
                // Do WiBroniks Areas
                if ($arr = scandir('polygons/lte'))
                    foreach ($arr as $entry)
                        if (strlen($entry) > 4) {
                            ?>            
                            // Google Map Data
                            // -- Load GeoJSON.
                            map.data.loadGeoJson('polygons/lte/<?=$entry?>');                            
                            SetDefaultMapStyle();
                            <?php
                        }
                        ?>
                        lteAreasWasVisibility = true;
            }
        } else {
            map.data.setStyle({visible: false});
        }
    }
    function SetDefaultMapStyle() {
        // Color each letter gray. Change the color when the isColorful property
        // is set to true.
        map.data.setStyle(function(feature) {
        var ploygon_name = feature.getProperty('Name');
        var color = feature.getProperty('color');
        var icon = feature.getProperty('Icon');
        if (feature.getProperty('isColorful')) {
            color = feature.getProperty('color');
        }
        return /** @type {!google.maps.Data.StyleOptions} */({
                title: ploygon_name,
                fillColor: color,
                strokeColor: color,
                strokeWeight: 0,
                icon: icon
            });
        });
    }
    /**
     * The PolygonsControl adds a control to the map that can toggle polygons visibility
     * This constructor takes the control DIV as an argument.
     * @constructor
     */
    function CenterControl(controlDiv, map) {
        coverageBtns = new CoverageButtons(controlDiv, gponAreas, wimaxBases, lteBases , fiberAreas, lteAreas);
        
        // Setup the click event listeners: simply set the map to
        google.maps.event.addDomListener(coverageBtns.allBtnCnt, 'click', function () {
            if (baseVisibility) {
                baseVisibility = false;
            } else {
                gponVisibility = wiMaxVisibility = fiberVisibility = lteVisibility = lteAreasVisibility = false;
                baseVisibility = true;
            }
            
            MakeVisible(baseVisibility);
            coverageBtns.showCoverage(coverageBtns.allBtnCnt, baseVisibility);
        });  
        
        google.maps.event.addDomListener(coverageBtns.gponBtnCnt, 'click', function () {
            if (gponVisibility) {
                gponVisibility = false;
            } else {
                baseVisibility = wiMaxVisibility = fiberVisibility = lteVisibility = lteAreasVisibility = false;
                gponVisibility = true;
            }
            
            MakeVisible(false);
            coverageBtns.showCoverage(coverageBtns.gponBtnCnt, gponVisibility);
        });  
        
        google.maps.event.addDomListener(coverageBtns.wimaxBtnCnt, 'click', function () {
            if (wiMaxVisibility) {
                wiMaxVisibility = false;
            } else {
                baseVisibility = gponVisibility = fiberVisibility = lteVisibility = lteAreasVisibility = false;
                wiMaxVisibility = true;
            }
            
            MakeVisible(false);
            coverageBtns.showCoverage(coverageBtns.wimaxBtnCnt, wiMaxVisibility);
        });  
        
        google.maps.event.addDomListener(coverageBtns.lteBtnCnt, 'click', function () {
            if (lteVisibility) {
                lteVisibility = false;
            } else {
                baseVisibility = gponVisibility = fiberVisibility = wiMaxVisibility = lteAreasVisibility = false;
                lteVisibility = true;
            }
            
            MakeVisible(false);
            coverageBtns.showCoverage(coverageBtns.lteBtnCnt, lteVisibility);
        });  
        
        google.maps.event.addDomListener(coverageBtns.fiberBtnCnt, 'click', function () {
            if (fiberVisibility) {
                fiberVisibility = false;
            } else {
                baseVisibility = gponVisibility = wiMaxVisibility = lteVisibility = lteAreasVisibility = false;
                fiberVisibility = true;
            }
            
            MakeVisible(false);
            coverageBtns.showCoverage(coverageBtns.fiberBtnCnt, fiberVisibility);
        });   
        
        google.maps.event.addDomListener(coverageBtns.lteAreasBtnCnt, 'click', function () {
            if (lteAreasVisibility) {
                lteAreasVisibility = false;
            } else {
                baseVisibility = gponVisibility = wiMaxVisibility = lteVisibility = fiberVisibility = false;
                lteAreasVisibility = true;
            } 
            
            MakeVisible(lteAreasVisibility);
            coverageBtns.showCoverage(coverageBtns.lteAreasBtnCnt, lteAreasVisibility);
        }); 

        
    }
    function getInfo(latLng) {
        try {
            var found = false;
            var dist;
            var dist_least = 100000;
            // Try GPON 
            if (found == false)
                for (var x in gponAreas)
                    if (google.maps.geometry.poly.containsLocation(latLng, gponAreas[x].shape)) {
                        placeMarker(latLng, gponAreas[x].shape.code, gponAreas[x].shape.zone, gponAreas[x].shape.tech, gponAreas[x].shape.title);
                        found = true;
                        break;
                    }
            
            // Try Fibre
            if (found == false)
                for (var x in fiberAreas)
                    if (google.maps.geometry.poly.containsLocation(latLng, fiberAreas[x].shape)) {
                        placeMarker(latLng, fiberAreas[x].shape.code, fiberAreas[x].shape.zone, fiberAreas[x].shape.tech, fiberAreas[x].shape.title);
                        found = true;
                        break;
                    }
            
            // Try WiBroniks Base Stations
            if (found == false)
                for (var x in lteBases) {
                    dist = google.maps.geometry.spherical.computeDistanceBetween(lteBases[x].shape.center, latLng);
                    if (dist <= 4000) {
                        if (dist < dist_least) {
                            dist_least = dist;
                            placeMarker(latLng, '', '', 'LTE', lteBases[x].shape.title);
                            found = true;
                        }
                    }
                }
            
            // Try WiBroniks Areas
            if (found == false)
                for (var x in lteAreas)
                    if (google.maps.geometry.poly.containsLocation(latLng, lteAreas[x].shape)) {
                        placeMarker(latLng, lteAreas[x].shape.code, lteAreas[x].shape.zone, lteAreas[x].shape.tech, lteAreas[x].shape.title);
                        found = true;
                        break;
                    }
            
            
            // Try Wimax
            if (found == false)
                for (var x in wimaxBases) {
                    dist = google.maps.geometry.spherical.computeDistanceBetween(wimaxBases[x].shape.center, latLng);
                    if (dist <= 4000) {
                        if (dist < dist_least) {
                            dist_least = dist;
                            placeMarker(latLng, '', '', 'WiMax', wimaxBases[x].shape.title);
                            found = true;
                        }
                    }
                }
            // Try Wimax
            if (found == false) {
                placeMarker(latLng, '', '', 'VSAT', 'Zimbabwe');
            }
        } catch (e) {
            alert(e);
            placeMarker(latLng, '', '', 'VSAT', 'Zimbabwe');
        }
    }
    function getTechDescription(technology) {
        if (technology.toLowerCase() == 'gpon')
            return '<span class="fibroniks"><img src="img/fibroniks-logo.png" /><small>Fibroniks</small></span>';
        else if (technology.toLowerCase() == 'lte')
            return '<span class="wimax"><img src="img/broadband-logo.png" /><small>WiBroniks</small></span>';
        else if (technology.toLowerCase() == 'fiber' || technology.toLowerCase() == 'fibre')
            return '<span class="fiber"><img src="img/fiber-logo.png" /><small>Fibre (MPLS)</small></span>';
        else if (technology.toLowerCase() == 'lteareas')
            return '<span class="wimax"><img src="img/broadband-logo.png" /><small>WiBroniks</small></span>';
        else if (technology.toLowerCase() == 'wimax')
            return '<span class="wimax"><img src="img/broadband-logo.png" /><small>WiMax</small></span>';
        else if (technology.toLowerCase() == 'vsat')
            return '<span class="vsat"><img src="img/vsat-logo.png" /><small>VSAT</small></span>';
        else
            return technology;
    }
    function placeMarker(latLng, zone, area_code, technology, area_name) {
        jQuery('input[name=lat]').val(latLng.lat());
        jQuery('input[name=lng]').val(latLng.lng());
        jQuery('input[name=zone]').val(zone);
        jQuery('input[name=code]').val(area_code);
        jQuery('input[name=tech]').val(technology);
        jQuery('input[name=area]').val(area_name);
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK)
                if (results[0])
                    jQuery('input[name=formatted_address]').val(results[0].formatted_address);
            // Only offer for Zimbabwe
            if (jQuery('input[name=formatted_address]').val().toLowerCase().indexOf('zimbabwe') > -1) {
                jQuery('#reset').html("<span>" + jQuery('input[name=formatted_address]').val() +
                                      "<br/>" + area_name +
                                      "<br/><br/><strong>Latitude</strong><br>" + latLng.lat() +
                                      "<br><strong>Longitude</strong><br>" + latLng.lng() +
                                      "</span>");
                jQuery('#tech').html(getTechDescription(technology));
            } else {
                jQuery('#reset').html(jQuery('input[name=formatted_address]').val());
                jQuery('#btnNext, #btnNextTop').hide();
                if (latLng.lat() != 0 || latLng.lat() != 0)
                    alert('Our services are only available in Zimbabwe.');
            }


        });
        baseMarker.setPosition(latLng);
        baseMarker.setTitle(jQuery('#reset').val());
        FindByLatLng(latLng);
        
        showDetails();
        map.panTo(latLng);
    }
    function FindByLatLng(latLng) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    jQuery('input[name=formatted_address]').val(results[0].formatted_address);
                }
            }
        });
    }
</script>


<div id="map-canvas" class="map-canvas" style="overflow: hidden;"></div>
<div class="formElements">
    <form id="frmStart" method="POST" style="width: 100%; height: 100%;">
        <input name="formatted_address" type="hidden" value="" id="formatted_address"/>
        <input name="area" type="hidden" value="" id="area"/>
        <input name="lat" type="hidden" value=""/>
        <input name="lng" type="hidden" value=""/>
        <input type="hidden" name="code" value=""/>
        <input type="hidden" name="zone" value=""/>
        <input type="hidden" name="tech" value=""/>
        <div class="selectionWrapper">
            <h3 class="details">Location Details</h3>
            <div id="reset"></div>
            <div id="tech"></div>
        </div>
        
    </form>
</div>

<?php require 'footer.php'; ?>
