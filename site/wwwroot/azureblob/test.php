<?php
error_reporting(E_ALL);
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');
//$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME1').";AccountKey=".getenv('ACCOUNT_KEY1');

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);

$fileToUpload = "LargeZip.zip";
//$fileToUpload = "articles.jpg";


    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();
	
	$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
		
	// Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    $containerName = "files";
	//$blobClient->createContainer($containerName, $createContainerOptions);
	//$myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
	//fclose($myfile);
	
	# Upload file as a block blob
	echo "Uploading BlockBlob: ".PHP_EOL;
	echo $fileToUpload;
	echo "<br />";
	
	$content = fopen($fileToUpload, "r");
	print_r($content);

	//Upload blob
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);	

	
	