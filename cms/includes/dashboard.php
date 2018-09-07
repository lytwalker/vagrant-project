<?php
include 'shared/header.php';
include 'shared/base_station.php';

$dbUtils = new DbUtils ();
$list = getBaseStations ();

?>
<div class="right_col" role="main">
	<h3>Dashboard</h3>
	<div id="stats">
		<h2>Statistics</h2>
	</div>	
	<div class="map">	
		<h2>Map</h2>	
		<div id="map">
		</div>	
	</div>
</div>
<?php 
	include 'shared/footer.php';
?>