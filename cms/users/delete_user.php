<?php 
include '../shared/header.php';
include '../shared/user.php';

$dbUtils = new DbUtils();

if(!empty($_GET['userid'])){	
	$res = $dbUtils->DeleteQuery("Delete FROM users", $_GET['userid']);
	if($res){
		echo "<p>Base station successfully deleted</p>";
	}else {
		echo "<p>Deletion failed</p>";
	}
}
 
//failed to set user Id, exit
if(empty($user_id)){
	header("Location:/cms/users/");
	exit;
}

?>