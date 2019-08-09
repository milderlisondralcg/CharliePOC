<?php
error_reporting(E_ALL);
require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Create connection to BLOB Storage
if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME').";AccountKey=".getenv('MM_BLOB_KEY'); // Golf/Development
	define("PROCESSED_URL", "https://charlie.coherent.com/go/");
	define("DIRECT_TO_FILE_URL", "https://pocmarcomgolfstorage.blob.core.windows.net/"); // Golf
}else{
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME_PROD').";AccountKey=".getenv('MM_BLOB_KEY_2_PROD'); // COHRstage
	define("PROCESSED_URL", "https://cohrstage.coherent.com/go/");
	define("DIRECT_TO_FILE_URL", "https://content.coherent.com/"); // Production
}
$mm_table = "mm_media";


// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
$containers = $blobClient->listContainers();

foreach ($containers->getContainers() as $container) {
	/* print '<pre>';
	print_r($container);
	print '</pre>'; */
	//echo "Container: " . $container->getName() . '<br/>';
	$num_elements = count(explode("-",$container->getName()));
	if( $num_elements == 1 ){
		print ucfirst($container->getName())  . '<br/>';
	}
	

}