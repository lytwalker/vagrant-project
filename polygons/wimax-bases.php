<?php

$type = "wimax";
$list = getActiveBaseStationsByType($type);
$bases = "";
while($row = $list->fetch_assoc()) {
	$bases .= "wimaxBases['".$row['Id']."']={shape:new google.maps.Circle({center:new google.maps.LatLng(".$row['Latitude'].",".$row['Longitude']."),title:'".$row['Name']."',radius:". 10/*($row['Range']/100) */.",strokeColor:'black', strokeOpacity:0.8,strokeWeight:5,fillColor:'orange',fillOpacity:0.35,map:map, visible:false })};";
}

echo $bases;