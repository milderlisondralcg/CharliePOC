<?php
session_start();
error_reporting(E_ERROR);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$media = new Media(); // media models/
$data = array();
$action = $_REQUEST['action'];

switch($action){
	case "publish":
	case "get_business":
		if(isset($_REQUEST['type'])){
			$product_group = strtolower(trim($_POST['product_group']));
			$result = $media->get_by_group($product_group);	
		}else{
			$product_family = trim($_POST['product_family']);
			$result = $media->get_media_by_folder($product_family);				
		}
		//$product_family = trim($_POST['product_family']);
		//$result = $media->get_media_by_folder($product_family);		
		$data = array();
		$MimeType = "";
		foreach($result as $key=>$value){
			
			
			$current_category = $value['Category'];			
			extract($value);
			if (!isset($data[$current_category])) {
				if($current_category != $data[$current_category]){
					$data[$current_category];
				}
			}
			$value = explode('.', $SavedMedia);
			$extension = strtolower(end($value));
			$MimeType = detectByFileExtension($extension);

			$data[$current_category][] = array(
						"Title"=>$Title,
						"SeoUrl"=>$SeoUrl,
						"Type"=>$Type,
						"SavedMedia"=>$SavedMedia,
						"Folder"=>$Folder,
						"Category"=>$Category,
						"Tags"=>$Tags,
						"MimeType"=>$MimeType
						);		
	  
		};
		
		foreach($data as $k=>$v){
			if(gettype($k) != "integer"){
				$data[$k] = array_sort($v, "Tags",SORT_ASC); // sort current array by specific key
			}else{
				unset($data[$k]);
			}
		}
	
		foreach($data as $key=>$value){
			foreach($value as $item=>$item_data){
				if(gettype($item) == "integer"){
					unset($data[$key][$item]);
				}
				// set current Tag
				$current_tag = $item_data['Tags']; 
				
				if (!isset($data[$key][$current_tag])) {
					if($current_tag != $data[$key][$current_tag]){
						$data[$key][$current_tag];	
					}
				}
					extract($item_data);
					$value = explode('.', $SavedMedia);
					$extension = strtolower(end($value));
					$MimeType = detectByFileExtension($extension);					
					$data[$key][$current_tag][] = array(
						"Title"=>$Title,
						"SeoUrl"=>$SeoUrl,
						"Type"=>$Type,
						"SavedMedia"=>$SavedMedia,
						"Folder"=>$Folder,
						"Category"=>$Category,
						"Tags"=>$Tags,
						"MimeType"=>$MimeType
						);	
				
			}
		}	


$html_output = '';
foreach( $data as $key=>$value){
	
$html_output .='				
<h2>'.$key.'</h2>
<div class="summary">
  <div class="content-container">
    <div class="resource-content">
      <div class="service-doc-cont">';
      
	foreach($value as $tag=>$items){
		$html_output .='<div class="resource-item">
	<div class="form_accordion" class="accordion-form">
		<div class="toggler">
			<div class="icon"> <img align="absmiddle" src="/assets/site_images/minus.png" alt="Toggle" direction="up"> </div>
			<div class="related-title resource-prod"><h2>'.$tag.'</h2></div>
		</div>';
		
		foreach($items as $item=>$item_data){
			extract($item_data);
			
			$value = explode('.', $SavedMedia);
			$extension = strtolower(end($value));
			$MimeType = detectByFileExtension($extension);		
					
			switch($MimeType){
				case "application/pdf":
					$url_download_icon = '/assets/site_images/small-pdf-icon.png';
					break;
				case "application/x-zip":
					$url_download_icon = '/assets/site_images/zip-icon.png';
					break;
				default:
					$url_download_icon = '/assets/site_images/small-dl-icon.png';
					break;
			}
			
			$url_download = 'https://content.coherent.com/optoskand/' . $SeoUrl;
			$url_download = 'https://content.coherent.com/' . $Folder . '/' . $SeoUrl;
							
			$html_output .= '<div class="resource-container" style="display: none;">
				<div class="clear"></div>
				<div class="support-doc-box resource-center-support-doc-box ">
					<div class="thumb">
						<a href="'.$url_download.'" target="_blank"><img src="'.$url_download_icon.'" alt="Coherent Download"></a>
					</div>
					<div class="support-box-cont">
						<div class="title"><a href="'.$url_download.'" target="_blank">'. $Title .'</a></div>
							<div class="summary"></div>
					</div>
				</div>
			</div>';
		}
	$html_output .='<hr>
	</div>
</div>';
	}

      $html_output .='</div>
  </div>
</div>
</div>
';
}
				
		print $html_output;
				
		$dest_directory = "../../App_Constant/";
		//$filename = "optoskand.html";
		if(isset($_REQUEST['type'])){
			$filename = $product_group . ".html";
		}else{
			$filename = $product_family . ".html";
		}
		//$filename = $product_family . ".html";
		$dest_path = $dest_directory .  $filename;
		$handle = fopen($dest_path, 'w') or die('Cannot open file:  '.$dest_path);
		//$string_to_write = json_encode($data);
		$string_to_write = $html_output;
		fwrite($handle, $string_to_write);
		break;
}

		
function detectByFileExtension($extension) {    
    $extensionToMimeTypeMap = getExtensionToMimeTypeMap();

    if (isset($extensionToMimeTypeMap[$extension])) {
        return $extensionToMimeTypeMap[$extension];
    }
    return 'text/plain';
}

function getExtensionToMimeTypeMap() {
    return [
        'hqx'   => 'application/mac-binhex40',
        'cpt'   => 'application/mac-compactpro',
        'csv'   => 'text/x-comma-separated-values',
        'bin'   => 'application/octet-stream',
        'dms'   => 'application/octet-stream',
        'lha'   => 'application/octet-stream',
        'lzh'   => 'application/octet-stream',
        'exe'   => 'application/octet-stream',
        'class' => 'application/octet-stream',
        'psd'   => 'application/x-photoshop',
        'so'    => 'application/octet-stream',
        'sea'   => 'application/octet-stream',
        'dll'   => 'application/octet-stream',
        'oda'   => 'application/oda',
        'pdf'   => 'application/pdf',
        'ai'    => 'application/pdf',
        'eps'   => 'application/postscript',
        'ps'    => 'application/postscript',
        'smi'   => 'application/smil',
        'smil'  => 'application/smil',
        'mif'   => 'application/vnd.mif',
        'xls'   => 'application/vnd.ms-excel',
        'ppt'   => 'application/powerpoint',
        'pptx'  => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'wbxml' => 'application/wbxml',
        'wmlc'  => 'application/wmlc',
        'dcr'   => 'application/x-director',
        'dir'   => 'application/x-director',
        'dxr'   => 'application/x-director',
        'dvi'   => 'application/x-dvi',
        'gtar'  => 'application/x-gtar',
        'gz'    => 'application/x-gzip',
        'gzip'  => 'application/x-gzip',
        'php'   => 'application/x-httpd-php',
        'php4'  => 'application/x-httpd-php',
        'php3'  => 'application/x-httpd-php',
        'phtml' => 'application/x-httpd-php',
        'phps'  => 'application/x-httpd-php-source',
        'js'    => 'application/javascript',
        'swf'   => 'application/x-shockwave-flash',
        'sit'   => 'application/x-stuffit',
        'tar'   => 'application/x-tar',
        'tgz'   => 'application/x-tar',
        'z'     => 'application/x-compress',
        'xhtml' => 'application/xhtml+xml',
        'xht'   => 'application/xhtml+xml',
        'zip'   => 'application/x-zip',
        'rar'   => 'application/x-rar',
        'mid'   => 'audio/midi',
        'midi'  => 'audio/midi',
        'mpga'  => 'audio/mpeg',
        'mp2'   => 'audio/mpeg',
        'mp3'   => 'audio/mpeg',
        'aif'   => 'audio/x-aiff',
        'aiff'  => 'audio/x-aiff',
        'aifc'  => 'audio/x-aiff',
        'ram'   => 'audio/x-pn-realaudio',
        'rm'    => 'audio/x-pn-realaudio',
        'rpm'   => 'audio/x-pn-realaudio-plugin',
        'ra'    => 'audio/x-realaudio',
        'rv'    => 'video/vnd.rn-realvideo',
        'wav'   => 'audio/x-wav',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'jpe'   => 'image/jpeg',
        'png'   => 'image/png',
        'gif'   => 'image/gif',
        'bmp'   => 'image/bmp',
        'tiff'  => 'image/tiff',
        'tif'   => 'image/tiff',
        'svg'   => 'image/svg+xml',
        'css'   => 'text/css',
        'html'  => 'text/html',
        'htm'   => 'text/html',
        'shtml' => 'text/html',
        'txt'   => 'text/plain',
        'text'  => 'text/plain',
        'log'   => 'text/plain',
        'rtx'   => 'text/richtext',
        'rtf'   => 'text/rtf',
        'xml'   => 'application/xml',
        'xsl'   => 'application/xml',
        'mpeg'  => 'video/mpeg',
        'mpg'   => 'video/mpeg',
        'mpe'   => 'video/mpeg',
        'qt'    => 'video/quicktime',
        'mov'   => 'video/quicktime',
        'avi'   => 'video/x-msvideo',
        'movie' => 'video/x-sgi-movie',
        'doc'   => 'application/msword',
        'docx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dot'   => 'application/msword',
        'dotx'  => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'word'  => 'application/msword',
        'xl'    => 'application/excel',
        'eml'   => 'message/rfc822',
        'json'  => 'application/json',
        'pem'   => 'application/x-x509-user-cert',
        'p10'   => 'application/x-pkcs10',
        'p12'   => 'application/x-pkcs12',
        'p7a'   => 'application/x-pkcs7-signature',
        'p7c'   => 'application/pkcs7-mime',
        'p7m'   => 'application/pkcs7-mime',
        'p7r'   => 'application/x-pkcs7-certreqresp',
        'p7s'   => 'application/pkcs7-signature',
        'crt'   => 'application/x-x509-ca-cert',
        'crl'   => 'application/pkix-crl',
        'der'   => 'application/x-x509-ca-cert',
        'kdb'   => 'application/octet-stream',
        'pgp'   => 'application/pgp',
        'gpg'   => 'application/gpg-keys',
        'sst'   => 'application/octet-stream',
        'csr'   => 'application/octet-stream',
        'rsa'   => 'application/x-pkcs7',
        'cer'   => 'application/pkix-cert',
        '3g2'   => 'video/3gpp2',
        '3gp'   => 'video/3gp',
        'mp4'   => 'video/mp4',
        'm4a'   => 'audio/x-m4a',
        'f4v'   => 'video/mp4',
        'webm'  => 'video/webm',
        'aac'   => 'audio/x-acc',
        'm4u'   => 'application/vnd.mpegurl',
        'm3u'   => 'text/plain',
        'xspf'  => 'application/xspf+xml',
        'vlc'   => 'application/videolan',
        'wmv'   => 'video/x-ms-wmv',
        'au'    => 'audio/x-au',
        'ac3'   => 'audio/ac3',
        'flac'  => 'audio/x-flac',
        'ogg'   => 'audio/ogg',
        'kmz'   => 'application/vnd.google-earth.kmz',
        'kml'   => 'application/vnd.google-earth.kml+xml',
        'ics'   => 'text/calendar',
        'zsh'   => 'text/x-scriptzsh',
        '7zip'  => 'application/x-7z-compressed',
        'cdr'   => 'application/cdr',
        'wma'   => 'audio/x-ms-wma',
        'jar'   => 'application/java-archive',
    ];
}


function array_sort($array, $on, $order=SORT_ASC){
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}