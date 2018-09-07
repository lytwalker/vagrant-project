<?php

$type = "lte";
$list = getBaseStationsByType($type);
$bases = "";
while($row = $list->fetch_assoc()) {
	$bases .= "lteBases['".$row['Id']."']=
    {
        shape:new google.maps.Circle(
            {
                center:new google.maps.LatLng(".$row['Latitude'].",".$row['Longitude']."),
                title:'".$row['Name']."',
                radius:".($row['Range']/10).",
                strokeColor:'blue', 
                strokeOpacity:0.8,
                strokeWeight:5,
                fillColor:'orange',
                fillOpacity:0.35,
                map:map, 
                visible:false 
            }
        )
    };";
}

echo $bases;