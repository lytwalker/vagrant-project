<?php

$type = "lte";
$list = getBaseStationsByType($type);
$bases = "";
while($row = $list->fetch_assoc()) {
    $bases .= 
<<< EndOfQuote
    {
        "type": "FeatureCollection",
    
        "features": [{
                "type": "Feature",
                "properties": {
                    "Name": "{$row['Name']}",
                    "Description": "<b>Name:<\/b> {$row['Name']}<br\/><b>Longitude:<\/b> {$row['Longitude']}<br\/><b>Latitude:<\/b> {$row['Latitude']}<br\/><b>Altitude:<\/b> 983<br\/>"
                },
                "geometry": {
                    "type": "Point",
                    "coordinates": [{$row['Longitude']}, {$row['Latitude']}, 0.0]
                }
            }
    
        ]
    }
EndOfQuote;
}

echo $bases;