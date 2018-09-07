<?php 
include 'config.php';

class DbUtils {
	private $hostname = CMSConf::DB_HOST;
	private $username = CMSConf::DB_USER;
	private $password = CMSConf::DB_PASS;
	private $database = CMSConf::DB_NAME;
	private $mysqli = null;
	
	public function __construct() {
		$this->Connect();
	}
	
	public function Connect(){
		$this->mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
		
		if ($this->mysqli->connect_errno) {
			echo("Connect failed: ". $this->mysqli->connect_error);
			return  false;
		}
		
		return $this->mysqli;
	}
	
	public function Sanitize($input) {
		return preg_replace("/[;']+/", '', $input);
	}
	
	// Executes a query and get the results
	public function SelectQuery($query_text) {
		try{
			$res =  $this->mysqli->query($query_text);
			return $res;
		}catch(Exception $ex){
			echo ($ex->message);
			return false;
		}
		
	}
	
	// Executes a query and get the results
	public function GenericQuery($query_text) {
		try{
			$res =  $this->mysqli->query($query_text);
			return $res;
		}catch(Exception $ex){
			echo ($ex->message);
			return false;
		}
	
	}
	
	public function InsertUpdateQuery($query_text, $id = 0){		
		try{
			if($id > 0){
				$res = $this->mysqli->query($query_text." WHERE Id=$id");
				if(!$res){
					return ('Error : '. $this->mysqli->error);
				}					
				
				return $id;					
					
			}else{				
				$res = $this->mysqli->query($query_text);
				if($res)
					return $this->mysqli->insert_id;
				else {					
					return ('Error : '. $this->mysqli->error);;
				}
					
			}
		}catch(Exception $ex){
			echo ($ex->message);
			return 0;
		}
		
	}
	public function DeleteQuery($query_text, $id = 0){
		$this->InsertUpdateQuery($query_text, $id);
	}
	/*https://www.sanwebe.com/2013/03/basic-php-mysqli-usage
	 */
}

?>