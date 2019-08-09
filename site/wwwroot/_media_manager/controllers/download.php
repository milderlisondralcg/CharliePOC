<?php
error_reporting(E_ALL);
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
$blobfilename = "73-vendor-documents---milder-lisondra.zip";
 $blob = $blobClient->getBlob("file", $blobfilename);

 header('Content-type: application/pdf');
 header("Content-Disposition: attachment; filename=\"myfile.zip\"");
 fpassthru($blob->getContentStream());
