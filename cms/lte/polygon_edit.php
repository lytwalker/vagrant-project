<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$id = 0;
$baseId = 0;
$Polygon_name = "";
$Polygon_description = "";
$Color = "";
$Icon = "";
$Coordinates = "";
$Decomissioned = "";
$Notes = "";

$dbUtils = new DbUtils ();

if (! (empty ( $_POST ))) {
	$id = $_POST ['id'];
	$baseId = $_POST ['baseid'];
	$Polygon_name = $_POST ['polygon_name'];
	$Polygon_description = $_POST ['polygon_description'];
	$Color = $_POST ['color'];
	$Icon = $_POST ['icon'];
	$Coordinates = $_POST ['coordinates'];
	$Decomissioned = isset ( $_POST ['decomissioned'] ) ? 1 : 0;
	$Notes = $_POST ['notes'];
	
	if ($id > 0) {
		$res = $dbUtils->InsertUpdateQuery ( " UPDATE base_stations_polygons SET
				`BaseStationId` = '$baseId' , 
				`Name` = '$Polygon_name' , 
				`Description` = '$Polygon_description' , 
				`Color` = '$Color' , 
				`Icon` = '$Icon' , 
				`Coordinates` = '$Coordinates' , 
				`Decomissioned` = '$Decomissioned' , 
				`Notes` = '$Notes'", $id );
	} else {
		$res = $dbUtils->InsertUpdateQuery ( "INSERT INTO base_stations_polygons (`BaseStationId`,`Name`,`Description`,`Color`,`Icon`,`Coordinates`,`Decomissioned`,`Notes`) 
	        VALUES ($baseId,'$Polygon_name','$Polygon_description','$Color','$Icon','$Coordinates','$Decomissioned','$Notes')" );
	}
	
	//get base station name
	$res_temp = getBaseStationPolygons($baseId);
	if ($res_temp !== false) {
		while ( $row = $res_temp->fetch_assoc () ) {
			$base_name = $row ['base_name'];
			break;
		}
	}
	
	//check if saved 
	if ($res > 0) {
		$id = $res;
		$msg = "<div class='alert alert-success alert-dismissible fade in'>Successfully saved</div>";
		if (!isset ( $_POST ['save'] )) {
			header ( "Location: /cms/lte/polygons.php?baseid=$baseId" );
			exit ();
		}
	} else {
		$msg = "<div class='alert alert-danger alert-dismissible fade in'>Failed to save " . $res."</div>";
	}
} elseif (! (empty ( $_GET ))) {
	if (! (empty ( $_GET ["baseid"] )) || $baseId > 0) {
		$baseId = $_GET ['baseid'];
		
		$res = getBaseStationPolygons ( $_GET ['baseid'] );
		if ($res !== false) {
			while ( $row = $res->fetch_assoc () ) {
				$base_name = $row ['base_name'];
				break;
			}
		}
	} else {
		header ( "Location:/cms/lte" );
		exit ();
	}
	
	if (! (empty ( $_GET ["id"] ))) {
		$id = $_GET ["id"];
		$res = getPolygon ( $id );
		
		while ( $row = $res->fetch_assoc () ) {
			$id = $row ['Id'];
			$baseId = $row ['BaseStationId'];
			$Polygon_name = $row ['Name'];
			$Polygon_description = $row ['Description'];
			$Color = $row ['Color'];
			$Icon = $row ['Icon'];
			$Coordinates = $row ['Coordinates'];
			$Decomissioned = $row ['Decomissioned'];
			$Notes = $row ['Notes'];
		}
	}
}

?>
<div class="right_col" role="main">
	<h3><?= ($Polygon_name != "" ? "Edit Polygon \"".htmlspecialchars($Polygon_name, ENT_QUOTES)."\"" : "Add Polygon") ." ".$base_name." Base Station" ?></h3>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<?= isset($msg)? $msg : "" ?>
				<form method="post" class="form-horizontal form-label-left">
					<div class="kpiname_group">
						<input type="hidden" name="id" value="<?= $id; ?>"> <input
							type="hidden" name="baseid" value="<?= $baseId; ?>">
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input required="true" type="text" name="polygon_name"
								value="<?= $Polygon_name; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Description</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea cols="50" rows="10" name="polygon_description" class="form-control col-md-7 col-xs-12 parsley-success"><?= $Polygon_description ?></textarea> 
							</div>						
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Color</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="color" value="<?= $Color; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Icon URL</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input  required="true" type="text"
								name="icon" value="<?= $Icon; ?>" class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Coordinates</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea cols="50" rows="10" name="coordinates" class="form-control col-md-7 col-xs-12 parsley-success"><?= $Coordinates ?></textarea> 
							</div>						
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Notes</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea cols="50" rows="10" name="notes" class="form-control col-md-7 col-xs-12 parsley-success"><?= $Notes ?></textarea> 
							</div>						
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<label for="decomissioned">
								<input type="checkbox" id="decomissioned" name="decomissioned" <?= $Decomissioned? "checked": "" ?> />							
									Pending activation?</label>
						    </div>
						</div>
					</div>
					
					<div class="ln_solid"></div>					
					
					<div class="btn_row col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<a href="polygons.php?baseid=<?= $baseId ?>"><i class="fa fa-arrow-circle-left"></i> Back</a> 
						<span class="pull-right"><input
							class="btn btn-primary" type="submit" name="save" value="Save" /> <input
							class="btn btn-primary" type="submit" name="save_exit"
							value="Save & Exit" />
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