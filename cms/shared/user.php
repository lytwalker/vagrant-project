<?php 
	include 'database.php';
	
	
	//get user by id
	function getUserById($id){
		$dbUtils = new DbUtils();
		
		return  $dbUtils->SelectQuery("SELECT * FROM users WHERE users.id = $id");
    }
    
	//login - get user by username & password
	function getUserByUsernamePassword($username, $password){
		$dbUtils = new DbUtils();
		$password = md5($password);
		
		return  $dbUtils->SelectQuery("SELECT * FROM users WHERE users.username = '".$username."' AND users.password = '".$password."'");
	}/**/
	
	//return users
	function getUsers(){
		$dbUtils = new DbUtils();
		
		return $dbUtils->SelectQuery("SELECT * FROM users");
	}
?>