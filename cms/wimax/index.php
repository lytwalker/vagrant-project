<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/base_station.php';

$type = "wimax";
$dbUtils = new DbUtils ();
if(!empty($_GET['ifa'])){
	$list = getFibroniksBaseStationsByType ($type);
	$title = "Base stations in Fibroniks areas";
}else if(!empty($_GET['nifa'])){
	$list = getNoneFibroniksBaseStationsByType ($type);
	$title = "Base stations not in Fibroniks areas";
}else if(!empty($_GET['d'])){
	$list = getDecomssionedBaseStationsByType ($type);
	$title = "Decomissioned base stations";
}else if(!empty($_GET['a'])){
	$list = getActiveBaseStationsByType ($type);
	$title = "Live base stations";
}else{
	$list = getBaseStationsByType ($type);
	$title = "Base stations";
}
?>
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3><?= $title; ?></h3>
                <div class="x_panel"> <a href="edit.php" class="btn btn-primary">Add Base Station</a>
                    <div class="x_content">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title"></th>
                                        <th class="column-title">Name</th>
                                        <th class="column-title">Lat,Long</th>
                                        <th class="column-title">Tilt</th>
                                        <th class="column-title">Range</th>
                                        <th class="column-title">Is Fibroniks Area?</th>
                                        <th class="column-title">Action</th>
                                    </tr>
                                </thead>
                                <?php while($row = $list->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="decomissioned" data-id="<?= $row['Id'] ?>">
                                            <?=  $row['Decomissioned']? "<span class='off' data-toggle='tooltip' title='".$row['Name']." was decomissioned, click to set live'></span>" : "<span class='live' data-toggle='tooltip' title='".$row['Name']." is Live, click to decomission'></span>" ?></td>
                                        <td>
                                            <?= $row['Name'] ?>
                                        </td>
                                        <td>(
                                            <?= $row['Latitude'].",".$row['Longitude']; ?>)</td>
                                        <td>
                                            <?= $row['Tilt'] ?>
                                        </td>
                                        <td>
                                            <?= $row['Range'] ?>
                                        </td>
                                        <td>
                                            <?=  $row['IsInFibroniksArea']? "Yes" : "No" ?>
                                        </td>
                                        <td class="action">
                                        <?php
                                            $accessLevel = (isset($_COOKIE["accessLevel"])) ? $_COOKIE["accessLevel"] : 0;
                                            if($accessLevel > 1 ){
                                            ?> 
                                            <a href="edit.php?baseid=<?= $row['Id'] ?>" data-name="<?= $row['Name'] ?>" title="Edit <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-pencil"></i></a> <a href="kpis.php?baseid=<?= $row['Id'] ?>" data-name="<?= $row['Name'] ?>" title="KPIs for <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-signal"></i></a> <a class="delete" href="delete_base.php?baseid=<?= $row['Id'] ?>" data-name="<?= $row['Name'] ?>" title="Delete <?= $row['Name'] ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
                                        <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
	include '../shared/footer.php';
?>