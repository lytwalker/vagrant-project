<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils ();

if (! empty ( $_GET ['baseid'] )) {
	
	$res = getBaseStationKpis ( $_GET ['baseid'] );
	
	if ($res !== false) {
		while ( $row = $res->fetch_assoc () ) {
			$id = $row ['Id'] === null ? 0 : $row ['Id'];
			$name = $row ['Name'];
			$frequency = $row ['Frequency'];
			$mechanicDowntilt = $row ['MechanicDowntilt'];
			$electronicDowntilt = $row ['ElectronicDowntilt'];
			$totalDowntilt = $row ['TotalDowntilt'];
			$antennaType = $row ['AntennaType'];
			$antennaHeight = $row ['AntennaHeight'];
			$cellPower = $row ['CellPower'];
			$remarks = $row ['Remarks'];
			$base_id = $row ['base_id'];
			$base_name = $row ['base_name'];
			break;
		}
	}
	
	$res = getBaseStationKpis ( $_GET ['baseid'] );
}

// failed to set base station Id, exit
if (empty ( $base_id )) {
	header ( "Location:/cms/wimax/" );
	exit ();
}

?>
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<h3>KIPs for <?= $base_name ?> base station</h3>
			<div class="x_panel">
				<a class="btn btn-primary" href="kpi_edit.php?baseid=<?= $base_id ?>">Add KPI</a>
				<div class="x_content">							
					<div class="table-responsive">
						<?php if($res !== false && $id > 0){?>
							<table class="table table-striped jambo_table bulk_action">
								<thead>
									<tr class="headings">
										<th class="column-title">Name</th>
										<th class="column-title">Frequency</th>
										<th class="column-title">Mechanic Downtilt</th>
										<th class="column-title">Electronic Downtilt</th>
										<th class="column-title">TotalDowntilt</th>
										<th class="column-title">CellPower</th>
										<th class="column-title">Action</th>
									</tr>
								</thead>
							<?php while($row = $res->fetch_assoc()) { ?>
							<tr>
									<td><?= $row['Name'] ?></td>
									<td><?= $row['Frequency'] ?></td>
									<td><?= $row['MechanicDowntilt'] ?></td>
									<td><?= $row['ElectronicDowntilt'] ?></td>
									<td><?= $row['TotalDowntilt'] ?></td>
									<td><?= $row['CellPower'] ?></td>
									<td class="action">
										<a
										href="kpi_edit.php?baseid=<?= $row['base_id'] ?>&id=<?= $row['Id'] ?>" data-name=" KPI <?= $row['Name'] ?>" title="Edit KPI <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-pencil"></i></a> 
										<a  class="delete"
										href="delete_kpi.php?id=<?= $row['Id'] ?>&baseid=<?= $row['base_id'] ?>" data-name=" KPI <?= $row['Name'] ?>" title="Delete KPI <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
									</td>
								</tr>	
							<?php } ?>
							</table>
						<?php }?>					
					</div>
					<div class="ln_solid"></div>	
					<a href="/cms/wimax/"><i class="fa fa-arrow-circle-left"></i> Back</a>
				</div>
			</div>
		</div>
	</div>

</div>
<?php 
	include '../shared/footer.php';
?>