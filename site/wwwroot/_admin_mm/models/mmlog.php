<?php

class MMLog extends Database{

	public $conn;
	protected $mm_log = 'mm_log_admin_action';
	protected $ts = 0;
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
		$this->ts = date("Y-m-d H:i:s",time()); // set current timestamp
	}
	
	public function get_by_id($media_id){
		$query = "SELECT * FROM `".$this->mm_log."` WHERE `Object` = '". $media_id ."'";
		$stmt = $this->conn->prepare($query);	
		$stmt->execute();
			if($stmt->rowCount() == 1) {
				$return_data = $stmt->fetch(PDO::FETCH_ASSOC);
				return $return_data;
			}elseif( $stmt->rowCount() > 1 ){
				$return_data = array();
				while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
				   $return_data[] = $result;
				}
				return $return_data;
			}else{
				return 0;
			}
	}

}