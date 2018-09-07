<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils ();

if (! empty ( $_GET ['baseid'] )) {
	
	$res = getBaseStationPolygons ( $_GET ['baseid'] );
	
	if ($res !== false) {
		while ( $row = $res->fetch_assoc () ) {
			$id = $row ['Id'] === null ? 0 : $row ['Id'];
			$name = $row ['Name'];
			$description = $row ['Description'];
			$color = $row ['Color'];
			$icon = $row ['Icon'];
			$coordinates = $row ['Coordinates'];
			$decomissioned = $row ['Decomissioned'];
			$notes = $row ['Notes'];
			$base_id = $row ['base_id'];
			$base_name = $row ['base_name'];
			break;
		}
	}
	
	$res = getBaseStationPolygons ( $_GET ['baseid'] );
}

// failed to set base station Id, exit
if (empty ( $base_id )) {
	header ( "Location:/cms/lte/" );
	exit ();
}

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h3>Polygons for <?= $base_name ?> base station</h3>
			<div class="x_panel">
				<a class="btn btn-primary" href="polygon_edit.php?baseid=<?= $base_id ?>">Add Polygon</a>
				<div class="x_content">							
					<div class="table-responsive">
						<?php if($res !== false && $id > 0){?>
							<table class="table table-striped jambo_table bulk_action">
								<thead>
									<tr class="headings">
                                        <th class="column-title"></th>
										<th class="column-title">Name</th>
										<th class="column-title">Description</th>
										<th class="column-title">Color</th>
										<th class="column-title">Icon</th>
										<th class="column-title">Action</th>
									</tr>
								</thead>
							<?php while($row = $res->fetch_assoc()) { ?>
							<tr>
									<td class="decomissioned" data-id="<?= $row['Id'] ?>">
										<?=  $row['Decomissioned']? "<span class='off' data-toggle='tooltip' title='".$row['Name']." was decomissioned, click to set live'></span>" : "<span class='live' data-toggle='tooltip' title='".$row['Name']." is Live, click to decomission'></span>" ?></td>
									<td><?= $row['Name'] ?></td>
									<td><?= $row['Description'] ?></td>
									<td><?= $row['Color'] ?></td>
									<td><?= $row['Icon'] ?></td>
									<td class="action">
										<a
										href="polygon_edit.php?baseid=<?= $row['base_id'] ?>&id=<?= $row['Id'] ?>" data-name=" Polygon <?= $row['Name'] ?>" title="Edit Polygon <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-pencil"></i></a> 
										<a  class="delete"
										href="delete_polygon.php?id=<?= $row['Id'] ?>&baseid=<?= $row['base_id'] ?>" data-name=" Polygon <?= $row['Name'] ?>" title="Delete Polygon <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
									</td>
								</tr>	
							<?php } ?>
							</table>
						<?php }?>					
					</div>
					<div class="ln_solid"></div>	
					<a href="/cms/lte/"><i class="fa fa-arrow-circle-left"></i> Back</a>
				</div>
			</div>
		</div>
	</div>

</div>
<?php 
	include '../shared/footer.php';
?>