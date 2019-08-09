<?php
header('Content-Type: application/json');
include("config/config.php");
include("config/database.php");
include("libraries/functions.php");
include("models/products.php");
include("models/productadmin.php");

// instantiate database and product object
$database = new Database();
$db_conn = $database->get_connection();

$products = new Products($db_conn);
$product_admin = new ProductAdmin($db_conn);

$action = $_REQUEST["action"];
if( isset($_REQUEST["id"]) ){
	$id = trim($_REQUEST["id"]);
}

if( isset($_GET["output"]) ){
	$output = "explode";
}
$output = "json";	
	switch($action){
	
		case "admin_save":
			$product = "";
			$product['id'] = $_POST['id'];
			if( strpos($_POST['spec_name'],"uom") > 0 ){
				$temp_length = strlen($_POST['spec_name']);
				$product["attribute"] = substr($_POST['spec_name'],0,$temp_length-4);
				$product["attribute"] = substr($product["attribute"],5);
				$product["attribute_type"] = "uom";
			}else{
				$product["attribute"] = substr($_POST['spec_name'],5);
			}
			
			$product["attribute_value"] = $_POST['spec_value'];
			$product["attribute_value"] = $_POST['spec_value'];
			$user = $_POST['user'];
			$product['user'] = $_POST['user'];

			// get current attribute data
			$current_attribute_data = $product_admin->get_attribute($product);
			//print $product_admin->save_attribute($product);
			if($product_admin->save_attribute($product)){
				$status = array("status"=>"saved");
				// log user and data modified ( before and after values )
/* 				try {					
					$log_data = array("previous_data"=>$current_attribute_data['Product_Attribute_Value'],"updated_data"=>$product["attribute_value"],"user"=>$user );
					$product_admin->log_admin_action($log_data);
				} catch (Exception $e) {
					die("Could not log action");
				} */				
			}else{
				$status = array("status"=>"failed");
			}
			print json_encode($status);
			break;
			
	}
