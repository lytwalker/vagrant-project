<?php 
include '../shared/header.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils();

if(!empty($_GET['id'])){	
	$res = $dbUtils->DeleteQuery("Delete FROM kpis", $_GET['id']);
	if($res){
		echo "<p>Kpi successfully deleted</p>";
	}else {
		echo "<p>Deletion failed</p>";
	}
}
 
//failed to set base station Id, exit
if(!empty($_GET['baseid'])){
	header("Location:/cms/wimax/kpis.php?baseid=".$_GET['baseid']);
	exit;
}else{
	header("Location:/cms/wimax");			
	exit;
}

?>