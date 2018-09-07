<?php 
	include 'database.php';
	
	
	//get base station by id
	function getBaseStationById($id){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE base_stations.Id = $id");
	}
	
	//return base stations
	function getBaseStations(){
		$dbUtils = new DbUtils();
		
		return $dbUtils->SelectQuery("SELECT * FROM base_stations");
	}
	function getBaseStationsByType($type){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE base_stations.Type = '".$type."'");
	}
	function getDecomssionedBaseStations(){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE Decomissioned = true");
	}
	function getDecomssionedBaseStationsByType($type){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE Decomissioned = true AND base_stations.Type = '".$type."'");
	}
	function getActiveBaseStations(){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations  WHERE Decomissioned = false");
	}
	function getActiveBaseStationsByType($type){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE Decomissioned = false AND base_stations.Type = '".$type."'");
	}
	function getFibroniksBaseStations(){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations  WHERE IsInFibroniksArea = true");
	}
	function getFibroniksBaseStationsByType($type){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations  WHERE IsInFibroniksArea = true AND base_stations.Type = '".$type."'");
	}
	function getNoneFibroniksBaseStations(){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE IsInFibroniksArea = false");
	}
	function getNoneFibroniksBaseStationsByType($type){
		$dbUtils = new DbUtils();
	
		return $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE IsInFibroniksArea = false AND base_stations.Type = '".$type."'");
	}
	
	// -- KPIs
	//base stations and kpis
	function getBaseStationKpis($baseId){
		$dbUtils = new DbUtils();
		
		return $dbUtils->SelectQuery("SELECT kpis.*, base_stations.Id as base_id, base_stations.Name as base_name  FROM  base_stations 
				LEFT JOIN kpis ON base_stations.Id = kpis.BaseStationId WHERE base_stations.Id = $baseId;");
	}
	
	//base stations and kpi
	function getKpi($id){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM kpis WHERE kpis.Id = $id");
	}
	
	// -- POLYGONs
	/*function getBaseStationsAndPolygonsByType($type){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations WHERE base_stations.Type = '".$type."'");
	}*/
	//base stations and polygons
	function getBaseStationPolygons($baseId){
		$dbUtils = new DbUtils();
		
		return $dbUtils->SelectQuery("SELECT base_stations_polygons.*, base_stations.Id as base_id, base_stations.Name as base_name  FROM  base_stations 
				LEFT JOIN base_stations_polygons ON base_stations.Id = base_stations_polygons.BaseStationId WHERE base_stations.Id = $baseId;");
	}
	
	//base stations and polygons
	function getPolygon($id){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM base_stations_polygons WHERE base_stations_polygons.Id = $id");
	}
?>