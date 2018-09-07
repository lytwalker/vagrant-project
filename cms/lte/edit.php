<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$id = 0;
$name = "";
$latitude = "";
$longitude = "";
$tilt = 0;
$range = 0;
$isInFibroniksArea = 0;
$decomissioned = 0;
$notes = "";
$type = "lte";

$dbUtils = new DbUtils ();

if (! (empty ( $_GET ))) {
	
	if (! (empty ( $_GET ["baseid"] ))) {
		$id = $_GET ["baseid"];
	} else {
		header ( "Location: /cms/lte/" );
	}
	
	// fetch info from db
	$res = getBaseStationById ( $id );
	while ( $row = $res->fetch_assoc () ) {
		$id = $row ['Id'];
		$name = $row ['Name'];
		$description = $row ['Description'];
		$icon = $row ['Icon'];
		$latitude = $row ['Latitude'];
		$longitude = $row ['Longitude'];
		$tilt = $row ['Tilt'];
		$range = $row ['Range'];
		$isInFibroniksArea = $row ['IsInFibroniksArea'];
		$decomissioned = $row ['Decomissioned'];
		$notes = $row ['Notes'];
	}
} elseif (! (empty ( $_POST ))) {
	$id = $_POST ['id'];
	$name = $_POST ['name'];
	$description = $_POST ['description'];
	$icon = $_POST ['icon'];
	$latitude = $_POST ['latitude'];
	$longitude = $_POST ['longitude'];
	$tilt = $_POST ['tilt'];
	$range = $_POST ['range'];
	$notes = $_POST ['notes'];
	
	$isInFibroniksArea = isset ( $_POST ['fibroniks'] ) ? 1 : 0;
	$decomissioned = isset ( $_POST ['decomissioned'] ) ? 1 : 0;
		
	if ($id > 0) {
		$res = $dbUtils->InsertUpdateQuery ( "Update base_stations set `Name` = '$name', `Description` = '$description', `Icon` = '$icon', `Latitude` =  '$latitude', 
				`Longitude` = '$longitude', `Tilt` = $tilt, `Range` = $range, `IsInFibroniksArea` = $isInFibroniksArea, 
				`Decomissioned` = $decomissioned, `Notes` = '$notes', `Type` = '$type'", $id );
	} else {
		$res = $dbUtils->InsertUpdateQuery ( "INSERT INTO base_stations (`Name`, `Description`, `Icon`, `Latitude`, `Longitude`, `Tilt`, `Range`, `IsInFibroniksArea`, `Decomissioned`, `Notes`, `Type`)
				VALUES ('$name', '$description', '$icon', '$latitude', '$longitude', $tilt, $range, $isInFibroniksArea, $decomissioned, '$notes', '$type')" );
	}
	
	if ($res > 0) {
		$id = $res;
		$msg = "<div class='alert alert-success alert-dismissible fade in'>Successfully saved</div>";
		if (!isset ( $_POST ['save'] )) {
			header ( "Location: /cms/lte/" );
			exit ();
		} 
	} else {
		$msg = "<div class='alert alert-danger alert-dismissible fade in'>Failed to save " . $res."</div>";
	}
}
?>
<div class="right_col" role="main">
	<h3><?= ($name != "" ? "Edit  ".$name : "Add")." Base Station"; ?></h3>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<?= isset($msg)? $msg : "" ?>
				<form method="post" action="edit.php" class="form-horizontal form-label-left">
						<input type="hidden" name="id" value="<?= $id ?>">
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="name"
								value="<?= $name ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Description</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea cols="50" rows="10" name="description" class="form-control col-md-7 col-xs-12 parsley-success"><?= $description ?></textarea> 
							</div>						
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Icon URL</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="icon" value="<?= $icon; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Latitude</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="latitude"
								value="<?= $latitude ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Longitude</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="longitude"
								value="<?= $longitude ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Tilt</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="tilt"
								value="<?= $tilt ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Range</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="range"
								value="<?= $range ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Notes</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea cols="50" rows="10" name="notes" class="form-control col-md-7 col-xs-12 parsley-success"><?= $notes ?></textarea> 
							</div>						
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<label for="decomissioned">
								<input type="checkbox" id="decomissioned" name="decomissioned" <?= $decomissioned? "checked": "" ?> />							
									Pending activation?</label> 
									
									
									<label for="fibroniks"><input type="checkbox"
									id="fibroniks" name="fibroniks"
									<?= $isInFibroniksArea? "checked" : "" ?> /> In Fibroniks Area?</label>
						    </div>
						</div>
						
						<div class="ln_solid"></div>
						
						<div class="btn_row col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							<a href="/cms/lte/" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Back</a> 
							<span class="pull-right">
								<input class="btn btn-primary" type="submit" name="save" value="Save" /> 
								<input class="btn btn-primary" type="submit" name="save_exit" value="Save & Exit" />
							</span>
						</div>
					</form>
			</div>
		</div>
	</div>
</div>

</div>
<?php 
	include '../shared/footer.php';
?>