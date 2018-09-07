<?php

include 'shared/user.php';

$dbUtils = new DbUtils ();
$thisScriptName = /*"login.php"*/ "../cms/index.php";

    $username = isset($_POST['username']) ? $_POST['username'] : '';
	
	if(isset($username) && ! (empty ( $username ))) {
        $password = isset($_POST['password']) ? $_POST['password'] : '';
		$md5password = md5($password);
		
		//		SELECT password for this user from the DB and see it it matches 
		$res = getUserByUsernamePassword ( $username, $password );
        if($res){
            while ( $row = $res->fetch_assoc () ) {
                $userId = $row ['id'];
                $passwordRetrieved = $row ['password'];
                $accesslevel = $row ['accesslevel'];
            }
            
            $passwordRetrieved = (isset($passwordRetrieved) && !empty($passwordRetrieved)) ? $passwordRetrieved : 'N/A';
			
			if (!empty($passwordRetrieved) AND ($md5password == $passwordRetrieved)) {
				//
				setcookie('loginAuthorised', 'authorised', time()+7200, '/');
				setcookie('accessLevel', $accesslevel, time()+7200, '/');
				setcookie('userId', $userId, time()+7200, '/');
				header('Location: ../cms/index.php');

				//include_once("includes/click_to_proceed.php");
			} else { ?>
            <link href="/img/zol_logo_broadband.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
            <link href="/img/zol_logo_broadband.png" rel="icon" type="image/vnd.microsoft.icon" />
            <link href="theme/css/custom.min.css" rel="stylesheet">
            <link href="theme/css/login.css" rel="stylesheet">
            <div class="login-page">
                <div class="form">
                    <h2>Access denied.</h2>
                    <br />
                    <br />
                    <?php echo '<a href="'.$thisScriptName.'">Try again</a>'; ?> </div>
                </div>
                <?php }
            }		
        } else {?>
            <link href="/img/zol_logo_broadband.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
            <link href="/img/zol_logo_broadband.png" rel="icon" type="image/vnd.microsoft.icon" />
            <link href="theme/css/custom.min.css" rel="stylesheet">
            <link href="theme/css/login.css" rel="stylesheet">
            <div class="login-page">
                <div class="form">
                    <form name="postLoginHid" <?php echo 'action="'.$thisScriptName. '"'; ?> method="post" class="register-form">
                        <h2>Sign In</h2>
                        <p>Please enter your username and password</p>
                        <input type=text name=username value="" size=12 maxlength=16 placeholder="Username">
                        <input type=password name=password value="" size=12 maxlength=16 placeholder="Password">
                        <input type="submit" value="Login" /> </form>
                </div>
            </div>
        <?php } ?>