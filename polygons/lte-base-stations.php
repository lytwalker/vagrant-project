<?php

$type = "lte";
$drawCircleFunction = "";
$joined = "";
$active_list = getActiveBaseStationsByType($type);
$active_num_rows = mysqli_num_rows($active_list); 
$active_count = 0;
$active_baseStations = "";

while($row = $active_list->fetch_assoc()) {
    $active_baseStations .= "drawCircle(new google.maps.LatLng(".$row['Latitude'].",".$row['Longitude']."), ".($row['Range']/10).", 1)";
    if ( $active_count < ( $active_num_rows - 1 ) )
        $active_baseStations .= ",";
    $active_count++;
}


// ---
$drawCircleFunction = "
function drawCircle(point, radius, dir)
{ 
    var d2r = Math.PI / 180;   // degrees to radians 
    var r2d = 180 / Math.PI;   // radians to degrees 
    var earthsradius = 3963; // 3963 is the radius of the earth in miles
    var points = 32; 

    // find the raidus in lat/lon 
    var rlat = (radius / earthsradius) * r2d; 
    var rlng = rlat / Math.cos(point.lat() * d2r); 

    var extp = new Array(); 
    if (dir==1) {var start=0;var end=points+1} // one extra here makes sure we connect the
    else{var start=points+1;var end=0}
    for (var i=start; (dir==1 ? i < end : i > end); i=i+dir)  
    {
        var theta = Math.PI * (i / (points/2)); 
        ey = point.lng() + (rlng * Math.cos(theta)); // center a + radius x * cos(theta) 
        ex = point.lat() + (rlat * Math.sin(theta)); // center b + radius y * sin(theta) 
        extp.push(new google.maps.LatLng(ex, ey));
    }
    return extp;
}";

$joined = $drawCircleFunction."
lteAreas['lte-base-stations.php'] = 
    {
        shape:new google.maps.Polygon(
            {
                paths: [".$active_baseStations."],
                strokeColor: 'green',
                strokeOpacity: 0.35,
                strokeWeight: 0,
                fillColor:'green',
                fillOpacity:0.35,
                map:map, 
                visible:false 
            }
         )
     };";

echo $joined;