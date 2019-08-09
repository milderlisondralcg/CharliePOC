<?php
$data = array();
$db_host = "us-cdbr-azure-west-b.cleardb.com";
$db_name = "pocmarcomwwwcharliedb";
$db_user = "be34d90920856c";
$db_pass = "804e507c";

            $conn = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pass);
            $conn->exec("set names utf8");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$category_id = 43;
$stmt = $conn->prepare('CALL sp_get_products_obis()');
$stmt->bindParam(1, $category_id, PDO::PARAM_INT);
$stmt->execute();	

$all_results = $stmt->fetchAll();
 
$filename = "json-files/obis-sql-" . date("m-d-Y-h-i-g", time()) . ".sql";
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);

	$large_array = array();	
		
	foreach( $all_results as $row ){
		$id = $row['ID'];

	   if (!isset($data[$id])) {
			 $data[$id] = array();
	   } 
		 $data[$id]['Name'] = $row['Name'];
		 $data[$id][$row['Product_Attribute']] = $row['Product_Attribute_Value'];
	}
	
	foreach($data as $key=>$value){
		$wavelength = $active_area_diameter = $min_energy = "";
		$max_energy = $repetition_Rate_max = $pulse_width_max = $power_min = $power_max = $cooling_method = "";
		$pc_interface = $measurement_type = $uncertainty = $beam_diameter = $pixel_size = $pulse_cw = "";
		$pulse_width = $operationmode = $technology = $mode = "";
		$url = "";
		$name = "";
		
		$name =  $data[$key]['Name'];

		if(isset( $data[$key]['WAVELENGTH'] )){
			$wavelength = $data[$key]['WAVELENGTH'];
		}
		if(isset( $data[$key]['OPERATIONMODE'] )){
			$operationmode = $data[$key]['OPERATIONMODE'];
		}
		if(isset( $data[$key]['TECHNOLOGY'] )){
			$technology = $data[$key]['TECHNOLOGY'];
		}
		if(isset( $data[$key]['MODE'] )){
			$mode = $data[$key]['MODE'];
		}	
		if(isset( $data[$key]['PULSE_WIDTH'] )){
			$pulse_width = $data[$key]['PULSE_WIDTH'];
		}
		
		if(isset( $data[$key]['URL'] )){
			$url = $data[$key]['URL'];
			$url = trim($url);
			$url_segments = explode("/",$url);
			print '<pre>';
			print_r($url_segments);
			print '</pre>';
			
			$last_segment = end($url_segments);
			if( isset($last_segment) && $last_segment <> "" ){
				$url = $last_segment;
			}
		}		
		
		$string_to_write = "INSERT INTO `magento_export` (`ID`,`Name`,`Wavelength`,`Operation_Mode`,`Technology`,`Mode`,`Pulse_Width`,`URL`)";
		$string_to_write .= " VALUE ('".$key."','".$name."','" . $wavelength . "','" .  $$operationmode . "','" . $technology. "','" .$mode."','" .$pulse_width."','" .$url."');";
		$string_to_write .= "\r\n";
		fwrite($handle, $string_to_write);	
	}	
	
	fclose($handle); 

			