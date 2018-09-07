<?php 
include '../shared/header.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils();

if(!empty($_GET['baseid'])){	
	$res = $dbUtils->DeleteQuery("Delete FROM base_stations", $_GET['baseid']);
	if($res){
		echo "<p>Base station successfully deleted</p>";
	}else {
		echo "<p>Deletion failed</p>";
	}
	
	//delete KPIs for this base station
	$res2 = $dbUtils->GenericQuery("Delete FROM kpis WHERE BaseStationId = " . $_GET['baseid']);
}
 
//failed to set base station Id, exit
if(empty($base_id)){
	header("Location:/cms/wimax/");
	exit;
}

?>