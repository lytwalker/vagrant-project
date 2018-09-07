<?php 
include '../shared/header.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils();

if(!empty($_GET['id'])){	
	$res = $dbUtils->DeleteQuery("Delete FROM base_stations_polygons", $_GET['id']);
	if($res){
		echo "<p>Polygon successfully deleted</p>";
	}else {
		echo "<p>Deletion failed</p>";
	}
}
 
//failed to set base station Id, exit
if(!empty($_GET['baseid'])){
	header("Location:/cms/lte/polygons.php?baseid=".$_GET['baseid']);
	exit;
}else{
	header("Location:/cms/lte");			
	exit;
}

?>