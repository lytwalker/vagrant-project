<?php
include '../shared/header.php';
include '../shared/access_levels/admin.php';
include '../shared/user.php';

$id = 0;
$username = "";
$password = "";
$email = "";
$accesslevel = 0;

$dbUtils = new DbUtils ();

if (! (empty ( $_GET ))) {
	
	if (! (empty ( $_GET ["userid"] ))) {
		$id = $_GET ["userid"];
	} else {
		header ( "Location: /cms/users" );
	}
	
	// fetch info from db
	$res = getUserById ( $id );
	while ( $row = $res->fetch_assoc () ) {
		$id = $row ['id'];
		$username = $row ['username'];
		$password = $row ['password'];
		$email = $row ['email'];
		$accesslevel = $row ['accesslevel'];
	}
} elseif (! (empty ( $_POST ))) {
	$id = $_POST ['id'];
	$username = $_POST ['username'];
	$password = md5($_POST ['password']);
	$email = $_POST ['email'];
	$accesslevel = $_POST ['accesslevel'];
		
	if ($id > 0) {
		$res = $dbUtils->InsertUpdateQuery ( "Update users set `username` = '$username', `password` =  '$password', 
				`email` = '$email', `accesslevel` =  '$accesslevel'", $id );
	} else {
		$res = $dbUtils->InsertUpdateQuery ( "INSERT INTO users (`username`, `password`, `email`, `accesslevel`)
				VALUES ('$username', '$password', '$email', '$accesslevel')" );
	}
	
	if ($res > 0) {
		$id = $res;
		$msg = "<div class='alert alert-success alert-dismissible fade in'>Successfully saved</div>";
		if (!isset ( $_POST ['save'] )) {
			header ( "Location: /cms/users/" );
			exit ();
		} 
	} else {
		$msg = "<div class='alert alert-danger alert-dismissible fade in'>Failed to save " . $res."</div>";
	}
}
?>
<div class="right_col" role="main">
	<h3><?= ($username != "" ? "Edit  ".$username : "Add")." User"; ?></h3>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_content">
				<?= isset($msg)? $msg : "" ?>
				<form method="post" action="edit.php" class="form-horizontal form-label-left">
						<input type="hidden" name="id" value="<?= $id ?>">
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Username</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="username"
								value="<?= $username ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>
							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="password" name="password"
								value="<?= $password ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Email</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input required="required" type="text" name="email"
								value="<?= $email ?>"  class="form-control col-md-7 col-xs-12 parsley-success">
							</div>							
						</div>
                  <?php
                  $accessLevel = (isset($_COOKIE["accessLevel"])) ? $_COOKIE["accessLevel"] : 0;
                  if($accessLevel > 1 ){
                    ?>
						<div class="input-row form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Access Level</label> 
							<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="radio" name="accesslevel" <?php if (isset($accesslevel) && $accesslevel=="0") echo "checked";?> value="0"> Generic<br/>
							<input type="radio" name="accesslevel" <?php if (isset($accesslevel) && $accesslevel=="1") echo "checked";?> value="1"> Editor<br/>
							<input type="radio" name="accesslevel" <?php if (isset($accesslevel) && $accesslevel=="2") echo "checked";?> value="2"> Administrator
							</div>							
						</div>
                  
				  <?php } ?>
						
						<div class="ln_solid"></div>
						
						<div class="btn_row col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							<a href="/cms/users/" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Back</a> 
							<span class="pull-right">
								<input class="btn btn-primary"
								type="submit" name="save" value="Save" /> <input
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