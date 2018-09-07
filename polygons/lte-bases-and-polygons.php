<?php

$ZCM_helper = new ZCM_helper();
$type = "lte";
$list = getBaseStationsByType($type);
$bases = "";
while($row = $list->fetch_assoc()) {

    // polygons
    $polygonlist = getBaseStationPolygons($row['Id']);
    $polygons = "";

    while($polygonrow = $polygonlist->fetch_assoc()) {
        if ( strlen($polygonrow['Name']) > 1 ) {

            // polygons
            $polygons .= 
<<< PolygonsQuote
            ,{
                "type": "Feature",
                "properties": {
                    "Name": "{$polygonrow['Name']}",
                    "Description": "{$polygonrow['Description']}",
                    "color": "{$polygonrow['Color']}"
                },
                "geometry": {
                    "type": "MultiPolygon",
                    "coordinates": [{$polygonrow['Coordinates']}]
                }
            } 
PolygonsQuote;
        }
    }

    // base station
    $bases = 
<<< EndOfQuote
    {
        "type": "FeatureCollection",    
        "features": [{
                "type": "Feature",
                "properties": {
                    "Name": "{$row['Name']}",
                    "Description": "<b>Name:<\/b> {$row['Name']}<br\/><b>Longitude:<\/b> {$row['Longitude']}<br\/><b>Latitude:<\/b> {$row['Latitude']}<br\/><b>Altitude:<\/b> 983<br\/>",
                    "Icon": "{$row['Icon']}"
                },
                "geometry": {
                    "type": "Point",
                    "coordinates": [{$row['Longitude']}, {$row['Latitude']}, 0.0]
                }
            }{$polygons}
        ]
    }
EndOfQuote;

    //echo "\n\n/**\n".$bases."\n*/\n\n";
    $filename = preg_replace('/[^a-zA-Z0-9_.()]/', '-', $row['Name']);
    $ZCM_helper->createFile($filename, "polygons/lte/", ".json", $bases);
}
