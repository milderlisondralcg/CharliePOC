<?php
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
    require("config.php");

/***************** Load Azure classes **************************/

require_once '../../../../../azureblob/vendor/autoload.php';
//require_once "./random_string.php";
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

    if(isset($_POST["data"])){
        $mapdata = $_POST["data"];
        $mapdata = iconv( "utf-8//IGNORE","cp1252", $mapdata);
        $myfile = fopen($mapspath."mapdata.js", "w");
        fwrite($myfile,"var simplemaps_worldmap_mapdata=".$mapdata.";");
        fclose($myfile);
    }
    if(isset($_POST["allproducts"])){
        $allproducts = array();
        $tools = json_decode(file_get_contents($toolspath), true);
        $lmc = json_decode(file_get_contents($lmcpath), true);
        $components = json_decode(file_get_contents($componentspath), true);
        $lasers = json_decode(file_get_contents($laserspath), true);
        for($i = 0; $i < count($lmc); $i++){
            array_push($allproducts, str_replace('"', '\"', $lmc[$i]["Name"]));
        }
        for($i = 0; $i < count($components); $i++){
            array_push($allproducts, str_replace('"', '\"', $components[$i]["Name"]));
        }
        for($i = 0; $i < count($tools); $i++){
            array_push($allproducts, str_replace('"', '\"', $tools[$i]["Name"]));
        }
        for($i = 0; $i < count($lasers); $i++){
            array_push($allproducts, str_replace('"', '\"', $lasers[$i]["Name"]));
        }
       
        $allproducts = array_unique($allproducts);
        $allproducts = array_values($allproducts);
        $removeallproducts = $mysqli->query("truncate maps_products");
        $insertproduct = $mysqli->prepare("INSERT INTO maps_products (product) VALUE (?)");
        $insertproduct->bind_param('s', $one);
        $mysqli->query("START TRANSACTION");
        foreach ($allproducts as $one) {
            $insertproduct->execute();
        }
        $insertproduct->close();
        $mysqli->query("COMMIT");
        $out = json_encode($allproducts, JSON_PRETTY_PRINT);
        print($out);
    }
    if(isset($_POST["attributes"])){
        if(isset($_POST["attributes"]["category"])){
            $stmt = $mysqli->prepare("UPDATE maps_categories SET category = ? WHERE category = ?");
            $stmt->bind_param('ss', $_POST["attributes"]["category"],$_POST["attributes"]["hidden"]);
            $stmt->execute();
        }else if(isset($_POST["attributes"]["product"])){
            $stmt = $mysqli->prepare("UPDATE maps_custom_products SET product = ? WHERE product = ?");
            $stmt->bind_param('ss', $_POST["attributes"]["product"],$_POST["attributes"]["hidden"]);
            $stmt->execute();
        }else if(isset($_POST["attributes"]["delete"])){
            $tmp = $_POST['attributes']['delete'];
            if($_POST["attributes"]["hidden"] == "categories[]"){
                $mysqli->query("DELETE FROM maps_categories WHERE category = '$tmp'");
                $mysqli->query("ALTER TABLE maps_categories DROP id");
                $mysqli->query("ALTER TABLE maps_categories ADD id int NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(id)");
            }else if($_POST["attributes"]["hidden"] == "products[]"){
                $mysqli->query("DELETE FROM maps_custom_products WHERE product = '$tmp'");
                $mysqli->query("ALTER TABLE maps_custom_products DROP id");
                $mysqli->query("ALTER TABLE maps_custom_products ADD id int NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(id)");
            }
        }else if(isset($_POST["attributes"]["insert"])){
            if($_POST["attributes"]["hidden"]=="categories[]"){
                $stmt = $mysqli->prepare("INSERT INTO maps_categories (category) VALUE (?)");
                $stmt->bind_param('s', $_POST["attributes"]["insert"]);
                $stmt->execute();
            }else if($_POST["attributes"]["hidden"]=="products[]"){
                $stmt = $mysqli->prepare("INSERT INTO maps_custom_products (product) VALUE (?)");
                $stmt->bind_param('s', $_POST["attributes"]["insert"]);
                $stmt->execute();
            }
        }
    }
    //if(isset($_POST["publish"])){
	if( ($_POST["action"] == "publish") || ($_POST["action"] == "publish_production") ){
		$mapspath = "D:\\home\\site\\wwwroot\\assets\\js\\simplemaps\\";
		$json_filename = "mapdata.js";
        $state = $mysqli->query("SELECT * FROM maps_state");
        $settings = $mysqli->query("SELECT * FROM maps_settings");
        $location = $mysqli->query("SELECT * FROM maps_location order by `group` DESC");
        $main_settings = array();
        $state_specific = array();
        $locations = array();
		
		// Settings
        while($row = $settings->fetch_assoc()){
            $main_settings[$row['name']] = $row['value'];
        };
		// States
        while($row = $state->fetch_assoc()){
            $group = array();
            $group['name'] = $row['name'];
            $tempContent = compileContent($row['content']);
            if($row['inactive'] == "no" && $tempContent != ""){
                //$group['description'] = $tempContent;
                $group['zoomable'] = $row['zoomable'];
                $group['color'] = $row['color'];
            }else{
                $group['inactive'] = "yes";
            }
            $state_specific[$row['abr']] = $group;
        };
		// Location (a.k.a Points in the Maps Updater admin )
        while($row = $location->fetch_assoc()){
            $group = array();
            $tempContent = compileContent($row['content']);
            if($tempContent != ""){
                $group['lat'] = $row['lat'];
                $group['lng'] = $row['lng'];
                $group['name'] = $row['name'];
                $group['description'] = compileContent($row['content']);
				//The following fields are new as of June 2019
                $group['border_color'] = $row['border_color'];
				$group['border'] = $row['border'];
				$group['color'] = $row['location_color'];
				$group['hover_color'] = $row['hover_color'];
				$group['group'] = $row['group'];
				
				if($row['group'] != "0" || $row['group'] <> "0" ){
					$group['image_url'] = $row['image_url'];
					$group['type'] = $row['type'];
					if( $row['group'] == "1"){
						$group['size'] = "19";
					}	
				}
				
                $locations[] = $group;
            }
        };
        $publishMap['main_settings'] = $main_settings;
        $publishMap['state_specific'] = $state_specific;
        $publishMap['locations'] = $locations;
/*         $myfile = fopen($mapspath."mapdata.js", "w");
        fwrite($myfile,"var simplemaps_worldmap_mapdata=".json_encode($publishMap, JSON_PRETTY_PRINT).";");
		fclose($myfile); */
		
		$filepath = $mapspath.$json_filename;
        $myfile = fopen($filepath, "w");
        fwrite($myfile,"var simplemaps_worldmap_mapdata=".json_encode($publishMap, JSON_PRETTY_PRINT).";");
		$handle = fopen($filepath, "r");
		$contents = fread($handle, filesize($filepath));

		// Publish to Production
		if( $_POST['action'] == "publish_production" ){
			//Upload JSON file to Azure Blob Storage
			$containerName = "maps-updater";
			$azure_upload_result = $blobClient->createBlockBlob($containerName, "mapdata.js", $contents);	
			print 'db prod';
			dbconnect_production();
		}

		fclose($myfile);	
		
    }
	
	// Retrieve data for Providers
    function compileContent($content){
        require("config.php");
		// Field maps_custom_products does not exist in table maps_providers
        $providers = $mysqli->query("SELECT title, address, phone, email, website, countries, additional, priority, visible, sales_phone, sales_email, service_phone, service_email, training_phone, training_email FROM maps_providers");
		$outArray = array();
        $out = "";

        while($row = $providers->fetch_assoc()){
            $list = explode(",!%%!,", $content);
			if(count($list) > 0){			
				foreach($list as $value){
					if($value == $row['title'] && $row['visible'] == "1"){
						$title = ""; $address = ""; $phone = ""; $email = ""; $website = ""; $additional = ""; $countries = "";
						$sales = "";
						$service = "";
						$training = "";
						
						if($row["title"] != ""){
							$title = "<span class='tt_subname_sm'>".$row['title']."</span><br>";
						}
						if($row["address"] != ""){
							$address = $row['address']."<br><br>";
						}
						if($row["phone"] != ""){
							$phone = $row["phone"]."<br>";
						}
						if($row["email"] != ""){
							$email = "<a href='mailto:".$row["email"]."'>".$row["email"]."</a><br>";
						}
						if($row["website"] != ""){
							$website = "<a href='".$row["website"]."' target='_blank'>".$row["website"]."</a><br>";
						}
						if( $row["sales_phone"] != "" || $row["sales_email"] != ""){
							$sales = '<br/><b>Sales</b><br/>';
							$sales .= 'Tel: ' . $row["sales_phone"] . '<br/>';
							$sales .= '<a href="mailto:'.$row["sales_email"].'">' . $row["sales_email"] . '</a><br/><br/>';
						}		
						if( $row["service_phone"] != "" || $row["service_email"] != "" ){
							$service = '<b>Service</b><br/>';
							$service .= 'Tel: ' . $row["service_phone"] . '<br/>';
							$service .= '<a href="mailto:'.$row["service_email"].'">' . $row["service_email"] . '</a><br/><br/>';
						}				
						if( $row["training_phone"] != "" || $row["training_email"] != "" ){
							$training = '<b>Training</b><br/>';
							$training .= 'Tel: ' . $row["training_phone"] . '<br/>';
							$training .= '<a href="mailto:'.$row["training_email"].'">' . $row["training_email"] . '</a>';
						}						
						if($row["additional"] === "<br>"){
							$additional = "";
						}elseif($row["additional"] != ""){
							$additional = $row["additional"]."<br>";
						}else{
							$additional = "";
						}
						if($row["countries"] != ""){
							$countries = "Locations Supported: ".str_replace(",!%%!,", ", ", $row["countries"]);
						}
						if(array_key_exists("products",$row)){
							$products_list = str_replace(",!%%!,", ", ", $row["products"]);
							$product = "<br><br><b>Products Supported:</b> " .$row["products_list"];
						}
						$outArray[$title . $address . $phone . $email . $website . $sales . $service . $training . $additional . $countries] = $row['priority'];
					}
				}
			}
        };
        if(!empty($outArray)){
            asort($outArray);
            $i = 0;
            $len = count($outArray);
            foreach($outArray as $key => $value){
                if($i == $len - 1){
                    $out = $out . $key;
                }else{
                    $out = $out . $key . "<br><br>";
                }
                $i++;
            }   
        }
        return $out;
    }
	
// DB Connection
function dbconnect_production(){
	$conn;
	print 'db prod';
	foreach ($_SERVER as $key => $value) {
		if (strpos($key, "DB_MYSQL_PROD_A1") !== 0) {
			continue;
		}
		
		$db_host = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
		$db_name = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
		$db_user = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
		$db_pass = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
		
		print $db_host;
	}		

/* 	try{
		$conn = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pass);
		$conn->exec("set names utf8");
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $exception){
		echo "Connection error: " . $exception->getMessage();
	} */

	//return $conn;
}	
?>