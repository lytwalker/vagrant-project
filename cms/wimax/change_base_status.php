<?php 
include '../shared/header.php';
include '../shared/base_station.php';

$dbUtils = new DbUtils();

if(!empty($_GET['id']) && !empty($_GET['status'])){	
	$decomissioned = $_GET['status'] == "live" ? 1 : 0;
	$id = $_GET['id'];
	
	$res = $dbUtils->InsertUpdateQuery ( "Update base_stations set 
				`Decomissioned` = $decomissioned", $id );
	if($res){
		$pass =  "<p>Base station successfully updated</p>";
	}else {
		$fail = "<p>Update failed</p>";
	}
}
?>
<div class="right_col" role="main">
	<h3>Please wait...</h3>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<p>Changing base station status, please wait... <?= $decomissioned."-".$id; ?><p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
if ('referrer' in document) {
	    window.location = document.referrer;
	} else {
		window.location.href = "/cms/wimax";
	}
</script>

<?php include '../shared/footer.php'; ?>
