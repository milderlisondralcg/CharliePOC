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
$stmt = $conn->prepare('CALL sp_get_products_by_category(?)');
$stmt->bindParam(1, $category_id, PDO::PARAM_INT);
$stmt->execute();	

$all_results = $stmt->fetchAll();
 
$filename = "json-files/magento-table-sql-" . date("m-d-Y-h-i-g", time()) . ".sql";
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
		$url = "";
		$name = "";
		
		$name =  $data[$key]['Name'];
		//print_r($key);
		if(isset( $data[$key]['WAVELENGTH'] )){
			$wavelength = $data[$key]['WAVELENGTH'];
		}
		if(isset( $data[$key]['ACTIVE_AREA_DIAMETER'] )){
			$active_area_diameter = $data[$key]['ACTIVE_AREA_DIAMETER'];
		}
		if(isset( $data[$key]['MIN_ENERGY'] )){
			$min_energy = $data[$key]['MIN_ENERGY'];
		}	
		if(isset( $data[$key]['MAX_ENERGY'] )){
			$max_energy = $data[$key]['MAX_ENERGY'];
		}		
		if(isset( $data[$key]['REPETITION_RATE_MAX'] )){
			$repetition_Rate_max = $data[$key]['REPETITION_RATE_MAX'];
		}
		if(isset( $data[$key]['PULSE_WIDTH_MAX'] )){
			$pulse_width_max = $data[$key]['PULSE_WIDTH_MAX'];
		}	
		if(isset( $data[$key]['POWER_MIN'] )){
			$power_min = $data[$key]['POWER_MIN'];
		}
		if(isset( $data[$key]['POWER_MAX'] )){
			$power_max = $data[$key]['POWER_MAX'];
		}		
		if(isset( $data[$key]['COOLING_METHOD'] )){
			$cooling_method = $data[$key]['COOLING_METHOD'];
		}			
		if(isset( $data[$key]['PC_INTERFACE'] )){
			$pc_interface = $data[$key]['PC_INTERFACE'];
		}
		if(isset( $data[$key]['MEASUREMENT_TYPE'] )){
			$measurement_type = $data[$key]['MEASUREMENT_TYPE'];
		}		
		if(isset( $data[$key]['UNCERTAINTY'] )){
			$uncertainty = $data[$key]['UNCERTAINTY'];
		}		
		if(isset( $data[$key]['Beam_Diameter'] )){
			$beam_diameter = $data[$key]['Beam_Diameter'];
		}
		if(isset( $data[$key]['Pixel_Size'] )){
			$pixel_size = $data[$key]['Pixel_Size'];
		}
		if(isset( $data[$key]['Pulse_CW'] )){
			$pulse_cw = $data[$key]['Pulse_CW'];
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
		
		$string_to_write = "INSERT INTO `magento_export` (`ID`,`Name`,`Wavelength`,`Active_Area_Diameter`,`Min_Energy`,`Max_Energy`,`Repetition_Rate_Max`,`Pulse_Width_Max`,`Power_Min`,`Power_Max`,`Cooling_Method`,`PC_Interface`,`Measurement_Type`,`Uncertainty`,`Beam_Diameter`,`Pixel_Size`,`Pulse_CW`,`URL`) ";
		$string_to_write .= " VALUE ('".$key."','".$name."','" . $wavelength . "','" .  $active_area_diameter . "','" . $min_energy. "','" .$max_energy."','" .$repetition_Rate_max."','" .$pulse_width_max."','" .$power_min."','" .$power_max."','" .$cooling_method."','" .$pc_interface."','" .$measurement_type."','" .$uncertainty."','" .$beam_diameter."','" .$pixel_size."','" .$pulse_cw."','".$url."');";
		$string_to_write .= "\r\n";
		fwrite($handle, $string_to_write);	
	}

		
	
	fclose($handle); 
		
		
 /*
$current_product_id = 0;
while ($row = $stmt->fetchObject()) {
	
	$current_product_id = $row->ID;
	print "current product id: " .  $current_product_id; 
	print '<br/>';
	if (!$data['current_product_id']) {
		$data['current_product_id']= new stdClass();
	}
	$data[$row->Product_Attribute] = $row->Product_Attribute_Value;  
	//print $current_product_id; 
	print '<pre>';
	print_r($data);
	print '</pre>';
	
	unset($data);
	
	

}
*/
/*
		foreach( $all_results as $row ){
			$id = $row['ID'];
	
		   if (!isset($data[$id])) {
				$data[$id] = new stdClass();
		   }			
		   
		   $attribute_name = strtolower($row['Product_Attribute']);
				if( $row['Product_Attribute_Value'] != ''){
					//$data[$id]->{$attribute_name} =  $row['Product_Attribute_Value'];
					$data[$id]->{$attribute_name}  =  array("value"=>$row['Product_Attribute_Value'],"uom"=>$row['Unit_Measurement']);
				//$data[$id]->{$attribute_name} =  array($row['Product_Attribute_Value'],$row['Unit_Measurement']);
				$data[$id]->ID = $id;
				$data[$id]->Name = $row['Name'];
				if( $attribute_name == "applications"){
					$data[$id]->application = $this->get_applications_by_product_v2($id);
				}
		   }


		}
		*/
			