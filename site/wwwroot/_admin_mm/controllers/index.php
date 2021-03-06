<?php
session_start();
error_reporting(E_ERROR);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media();
$auth = new Auth();
$mmlog = new MMLog();

/***************** Load Azure classes **************************/

require_once '../../azureblob/vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

// Create connection to BLOB Storage
if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME').";AccountKey=".getenv('MM_BLOB_KEY'); // Golf/Development
	define("PROCESSED_URL", "/go/");
	define("DIRECT_TO_FILE_URL", "https://pocmarcomgolfstorage.blob.core.windows.net/"); // Golf
}else{
	$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('MM_BLOB_NAME_PROD').";AccountKey=".getenv('MM_BLOB_KEY_2_PROD'); // COHRstage
	define("PROCESSED_URL", "/go/"); // Chenged to relative 5.20.19.cm
	define("DIRECT_TO_FILE_URL", "https://content.coherent.com/"); // Production
}
$blobClient = BlobRestProxy::createBlobService($connectionString);

/***************************************************************/


// Uploads location
$uploads_path = "../uploads/";
$action = "";

// check for action requested
if( isset($_POST['action']) ){
	$action = $_POST['action'];
}elseif( isset($_GET['action']) ){
	if( isset($_GET['user']) ){
		$MemberID = trim($_GET['user']);
		$action = "auth";		
	}
}

switch($action){
	case "update":
		extract($_POST);
		$media_info = $media->get($MediaID);
		extract($media_info);
		$result = $media->update($_POST);
		
		//check to see if the file type of incoming upload is the same as existing saved file
		$uploaded_filename = $_FILES['file_upload']['name'];
		$ext = strtolower(pathinfo($uploaded_filename, PATHINFO_EXTENSION));
		$existing_file_ext = explode(".",$SavedMedia);
		$existing_file_ext = $existing_file_ext[1];
		if( $ext != $existing_file_ext && $_FILES['file_upload']['size'] > 0){
			$response = array("result"=>"invalid","message"=>"File being uploaded must be the same file type as original file.");
		}else{
			// check if $_FILES exists along with other checks
			if(!empty($_POST['name']) || !empty($_POST['email']) || $_FILES['file_upload'] || $_FILES['file_upload']['size'] > 0){
				$uploaded_filename = $_FILES['file_upload']['name'];
				$existing_filename = explode(".",$SavedMedia);
				$media->update_lastfilename(array("MediaID"=>$MediaID,"LastFileName"=>$uploaded_filename));
				// get uploaded file's extension
				$ext = strtolower(pathinfo($uploaded_filename, PATHINFO_EXTENSION));
				$final_filename = $existing_filename[0] . "." . $ext;
				$tmp = $_FILES['file_upload']['tmp_name'];
				$moved_filename = trim(strtolower($uploaded_filename));
				$final_path = $uploads_path.$moved_filename; 
				if(move_uploaded_file($tmp,$final_path)) {
					$content = fopen($final_path, "r");
					//Upload media asset to Azure Blob Storage
					$azure_upload_result = $blobClient->createBlockBlob($Folder, $final_filename, $content);
				}
			}
			if($result){
				$response = array("result"=>"valid","message"=>"Your changes have been saved.");
			}else{
				$response = array("result"=>"invalid","message"=>"Invalid file type being uploaded");
			}
		}
		print json_encode($response);
		break;
	case "delete":
		extract($_POST);
		$media_info = $media->get($MediaID);

			$data['source_container'] = $media_info['Folder'];
			$data['source_blob'] = $media_info['SavedMedia'];
			$data['destination_blob'] = time() . "-" . $media_info['SavedMedia'];
			
			$data['destination_container'] = $media_info['Folder'] . '-archive';
			extract($data);

			if( copy_media($data) == true){				
				$log_data = array("user"=>$_SESSION['username'],"action"=>"Media Copied to Archive Directory","object"=>$MediaID,"previous_data"=>"N/A","updated_data"=>"N/A");
				$media->log_action($log_data); // Log admin action	
				delete_media($data);
				$media->delete($MediaID);
				print json_encode(array("result"=>true));
			}else{
				print json_encode(array("result"=>false));
			}
		break;
	case "get_home_list":
		$folder = "";
		if($_SESSION['group_id'] == 13 || $_SESSION['group_id'] == 14){
			$folder = "optoskand";
		}
		$result = $media->get_media_all($folder);
		if( $result !== 0){
			foreach( $result as $row){
				extract($row);

				$direct_link_to_file = DIRECT_TO_FILE_URL . $Folder . "/" . $SavedMedia;
				$public_link_to_file = PROCESSED_URL .  $SeoUrl;
				$all_links = '<a href="'.$direct_link_to_file.'" target="_blank">'.$direct_link_to_file.'</a>';

/* 				$log_data = $mmlog->get_by_id($MediaID);
				if( isset($log_data['Updated_Data']) ){
					$data_blob = json_decode($log_data['Updated_Data']);
					$all_links .= '<br/><br/>Original Filename: '. $data_blob->original_filename .'</a>';
				} */
				
				if( isset($LastFilename) && (is_string($LastFilename)) ){
					$all_links .= '<br/><br/>Last Filename Uploaded: '. $LastFilename . '</a>';
				}
				
				//$all_links = '<a href="'.$direct_link_to_file.'" target="_blank">'.$direct_link_to_file.'</a> ( CDN - Use This )<br/><br/>';
				//$all_links .= '<a href="'.$public_link_to_file.'" target="_blank">'.$public_link_to_file.'</a> ( Special Relative Link )';
				$last_modified = date("m/d/Y", strtotime($CreatedDateTime)); // friendly date and time format
				
				$all_media[] = array("DT_RowId"=>$MediaID,"Title"=>$Title,"Category"=>$Category,"Description"=>$Description,"LinkToFile"=>$all_links,"LastModified"=>$last_modified,"Tags"=>$Tags,"ActionDelete"=>"Archive","ActionEdit"=>"Edit","Folder"=>ucfirst($Folder),"Group"=>$Group);
			}
			print json_encode(array("data"=>$all_media));		
		}else{
			$empty_array = array();
			print json_encode(array("recordsTotal"=>0,"data"=>$empty_array));
		}	
		break;
	case "auth":
		$member_last_activity = $auth->get_last_activity($MemberID);
		extract($member_last_activity);
		if( calc_ts_diff($last_activity) > getenv('MM_AUTH_TIMEOUT') ){ // user has not been active inside EE control panel for more than 30 minutes
			header("Location: /_admin_mm/noaccess.php");
		}else{
			// Set username in session
			$_SESSION['username'] = $username;
			$_SESSION['group_id'] = $group_id;
			$_SESSION['member_id'] = $member_id;
			header("Location: /_admin_mm/index.php");
		}
		break;
	case "add":

			if($_FILES){
				$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc', 'docx' , 'ppt','tiff','zip','csv','xls','xlsx','sql','txt','gz'); // valid extensions

				if(!empty($_POST['name']) || !empty($_POST['email']) || $_FILES['file_upload']){
					$uploaded_filename = $_FILES['file_upload']['name'];
					$tmp = $_FILES['file_upload']['tmp_name'];

					// get uploaded file's extension
					$ext = strtolower(pathinfo($uploaded_filename, PATHINFO_EXTENSION));

					// Check that format/file type is valid
					if(in_array($ext, $valid_extensions)) { 

						$moved_filename = trim(strtolower($uploaded_filename));
						$final_path = $uploads_path.$moved_filename; 

						if(move_uploaded_file($tmp,$final_path)) {
							
							// Determine file category
							$folder = trim($_POST['Folder']);
							$category = trim($_POST['Category']);
							$file_mime_type = mime_content_type($final_path);
							$_POST['saved_media'] = $moved_filename;
							$result = $media->add($_POST);
							
							if( $result['result'] === true){
								
								$title_temp = strtolower($_POST['Title']);
								$title_temp = preg_replace('/[^a-zA-Z0-9\']/', '-', $title_temp); // remove special characters
								$title_temp = str_replace("'", '', $title_temp); // remove apostrophes
								$title_temp = trim(preg_replace('/-+/', '-', $title_temp), '-'); // remove double dash and trailing dash

								
								//$seo_url = $result['MediaID'] . "-" . $title_temp . "." . $ext;
								$seo_url = $title_temp . "." . $ext;
								//$azure_filename = $result['MediaID'] . "-" . $title_temp . "." . $ext;
								$azure_filename = $title_temp . "." . $ext;
								// Update the record with the filename actually stored in Azure and the properly formatted SeoUrl
								$update_data = array("SavedMedia"=>$azure_filename,"MediaID"=>$result['MediaID'], "SeoUrl"=>$seo_url);
								$media->update_savedmedia_seourl($update_data);
								$containerName = $folder;
								$content = fopen($final_path, "r");
								//Upload media asset to Azure Blob Storage
								try{
									$azure_upload_result = $blobClient->createBlockBlob($containerName, $azure_filename, $content);	
									$result['direct_url'] =  DIRECT_TO_FILE_URL . $containerName . "/" . $azure_filename ;
									$result['processed_url'] =  PROCESSED_URL . $seo_url ;
									
									$log_data = array("user"=>$_SESSION['username'],"action"=>"Add new media","object"=>$result['MediaID'],"previous_data"=>"N/A","updated_data"=>$file_mime_type);
									$log_data['user'] = $_SESSION['username'];
									$log_data['action'] = 'Add new media';
									$log_data['previous_data'] = 'N/A';
									$_POST['original_filename'] = $uploaded_filename;
									$_POST['saved_media'] = $azure_filename;
									$log_data['updated_data'] = json_encode($_POST);
									$media->log_action($log_data); // Log admin action
									$media->update_lastfilename(array("MediaID"=>$result['MediaID'],"LastFileName"=>$uploaded_filename));
									print json_encode($result);
								}catch( ServiceException $e ){
									// Archive the Media record just so that it is not visible within dashboard
									$media->delete($result['MediaID']);

									$error_message = 'Media attempted to store in Azure Blob Storage: ' . $azure_filename. "\r\n";
									$error_message .= 'Container to be used: ' . $containerName. "\r\n";
									$error_message .= "Error returned by Azure Blob Storage: \r\n";
									$error_message .= $e->getMessage();
									$log_data = array("user"=>$_SESSION['username'],"action"=>"Transfer media to Azure Blob Storage","object"=>$azure_filename,"previous_data"=>"N/A","updated_data"=>$error_message);
									$media->log_action($log_data); // Log admin action	
									$result = array();
									$result['result'] = 'upload error';
									
									print json_encode($result); 
								}
							}else{
								print json_encode($result);
							}
						}
					}else{
						$result['result'] = 'invalid';
						print json_encode($result); 
					}
				}
			}	
		break;
	case "check_session":
		
		$member_last_activity = $auth->get_last_activity($_SESSION['member_id']);
		extract($member_last_activity);		
		if( calc_ts_diff($last_activity) > getenv('MM_AUTH_TIMEOUT') ){ // user has not been active inside EE control panel for more than 30 minutes
			$response = array("status"=>"401");
		}else{
			$response = array("status"=>"200","last_member_activity"=>calc_ts_diff($last_activity), "threshold"=>getenv('MM_AUTH_TIMEOUT'));
		}
		print json_encode($response);
		break;
	case "add_video":
		print_r($_FILES);
		if($_FILES){
			$num_files_sent = count($_FILES['file_upload']['name']);
			for( $i=0; $i<=$num_files_sent; $i++ ){
				if( isset($_FILES['file_upload']['name'][$i]) ){
					print $_FILES['file_upload']['name'][$i];
					print "\r\n";
				}
			}
		}
		break;
		
	case "get_containers":
		
		break;
	case "get_log_data":
		$log_entries[] = array("Date"=>'Date Entered',"Action"=>'Action Taken',"Object"=>'Object or Item ID',"Previous Data"=>'Previous Data if applicable',"Updated Data"=>'if applicable, the data that was changed');
		print json_encode(array("data"=>$log_entries));
		break;

}

// copy blob from one container to another
function copy_media($data){
	global $blobClient;
	global $media;
	extract($data);
	$source_blob_location = $source_container . '/' . $source_blob;
	$destination_blob_location = $destination_container . '/' . $destination_blob;
	
	try{
		$blobClient->copyBlob($destination_container,$destination_blob, $source_container, $source_blob);
		$message = $source_blob_location . ' copied to ' . $destination_blob_location;
		$log_data = array("user"=>$_SESSION['username'],"action"=>"Copy blob successful during archive","object"=>$destination_blob,"previous_data"=>"N/A","updated_data"=>$message);
		$media->log_action($log_data); // Log admin action			
		return true;
	}catch( ServiceException $e ){
		$message = $source_blob_location . ' could no be copied to ' . $destination_blob_location;
		$message .= "\r\n" . $e->getErrorText();
		$log_data = array("user"=>$_SESSION['username'],"action"=>"Copy blob failed during archive","object"=>$destination_blob,"previous_data"=>"N/A","updated_data"=>$message);
		$media->log_action($log_data); // Log admin action			
		return false;
	}
	//$result = $blobClient->copyBlob($destination_container,$destination_blob, $source_container, $source_blob);
	//print_r($result);
	//[_etag:MicrosoftAzure\Storage\Blob\Models\CopyBlobResult:private]
	//print_r($result->_etag:MicrosoftAzure\Storage\Blob\Models\CopyBlobResult:private);
	//print_r($result->_etag);
	
	//print_r($result->_Etag);
}

// delete blob
function delete_media($data){
	global $blobClient;
	extract($data);
	try{
		$blobClient->deleteBlob($source_container, $source_blob);
		return true;
	}catch( ServiceException $e ){
		//print($e->getErrorText());
	}
	//$blobClient->deleteBlob($source_container, $source_blob);
}

// Calculate difference between given timestamp and current timestamp
// pass in a timestamp
function calc_ts_diff($ts){

	$ts1 = strtotime(date("m/d/Y g:i A",$ts));
	$ts2 = strtotime(date("m/d/Y g:i A",time()));
	$seconds_diff = $ts2 - $ts1;     
	return $seconds_diff;
}
