<?php

class Errors extends Database{

	public $conn;
	protected $log_table = "mm_log_admin_action";
	protected $ts = 0;
	
	public function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
		$this->ts = date("Y-m-d H:i:s",time()); // set current timestamp
	}

	public function get(){
		$query = "SELECT * FROM `".$this->log_table."` ORDER BY `created` DESC";
		
		$stmt = $this->conn->prepare($query);	
		$stmt->execute();

		if($stmt->rowCount() > 0) {
			while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			   $return_data[] = $result;
			}
			return $return_data;
		}else{
			return 0;
		}		
	}
	
	public function get_by_param(){
		
	}
}