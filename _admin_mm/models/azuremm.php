<?php

require_once '../azureblob/vendor/autoload.php';
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class AzureMM extends Database{
	
	public $conn;
	protected $ts = 0;
	
	function __construct(){
		$this->conn = parent::__construct(); // get db connection from Database model
		$this->ts = date("Y-m-d H:i:s",time()); // set current timestamp
	}
	
	public function get_containers(){
		$excluded_containers = $this->get_containers_excluded();
		// Create connection to BLOB Storage
		if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
			$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME').";AccountKey=".getenv('MM_BLOB_KEY'); // Golf/Development
		}else{
			$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME_PROD').";AccountKey=".getenv('MM_BLOB_KEY_2_PROD'); // COHRstage
		}
		$blobClient = BlobRestProxy::createBlobService($connectionString);
			
		$containers = $blobClient->listContainers();
		$containers_list_array = array();
		foreach ($containers->getContainers() as $container) {
			$num_elements = count(explode("-",$container->getName()));
			if( strpos($container->getName(),"archive") === false && in_array($container->getName(),$excluded_containers) === false ){
				$container_display_name = ucfirst($container->getName());
				$container_name = $container->getName();
				$containers_list_array[$container_name] = $container_display_name;
			}			
		}
		return $containers_list_array; 		
	}
	
	/**
	* get list of containers to be excluded
	* returns array
	*/
	private function get_containers_excluded(){
		
		$list = explode(",",strtolower(trim(getenv('MM_BLOB_NAME_FOLDERS_TO_EXCLUDE'))));
		return($list);
		
	}
}









