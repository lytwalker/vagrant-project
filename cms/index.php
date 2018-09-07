
<?php

$status = (isset($_GET["status"])) ? $_GET["status"] : "unset";
if(isset($status) AND ($status == "logout")){
	setcookie('loginAuthorised', '', time()-7200, '/');
	setcookie('accessLevel', '', time()-7200, '/');
	setcookie('userId', '', time()-7200, '/');
	$loginAuthorised = false;
} else {
	$loginAuthorised = (isset($_COOKIE["loginAuthorised"])) ? ($_COOKIE['loginAuthorised'] == 'authorised') : "unset";
}

if($loginAuthorised == 'authorised'){
	$contentFile = "includes/dashboard.php";
} else {
	$contentFile = "includes/login.php";
}
include_once($contentFile);

?>