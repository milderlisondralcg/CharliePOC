<?php
error_reporting(E_ALL);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media();

// get the title from the url
// retrieve the Media record using the retrieved seo_url
// using the Media record, get the SavedMedia info because it has the actual file name within Azure Storage
// using the Media record, get the Category in order to also get the container
if( isset($_GET['seo_url']) ){
	$media_seo_url = trim($_GET['seo_url']);
	if( $media->get_media_by_url($media_seo_url) != 0 ){
		$media_result = $media->get_media_by_url($media_seo_url);
		extract($media_result);
		$media_filename = $SavedMedia; 
		$filename = basename($media_filename);
		$ext = new SplFileInfo($filename);
		$file_extension = strtolower($ext->getExtension());
		$download_filename = str_replace(" ", "", $Title);
		$download_filename = str_replace("-","", $download_filename) . "." . $file_extension;
		switch( $file_extension ){
			case "pdf":
				$content_type = "application/pdf";
				break;
			case "zip":
				$content_type = "application/zip";
				break;
		}
		$media_container = $Category;
	}
}
require_once '../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('BLOB_NAME').";AccountKey=".getenv('BLOB_KEY');

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
$blob = $blobClient->getBlob($media_container, $media_filename);

header('Content-type: application/pdf');
header('Content-Disposition: attachment; filename="'.$download_filename.'"');
fpassthru($blob->getContentStream());
