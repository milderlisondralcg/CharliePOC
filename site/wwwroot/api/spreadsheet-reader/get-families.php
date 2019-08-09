<?php
// This helps to populate the families table

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'families.sql';
		$filetoread = 'Families-Only.xlsx';
		break;
	case "b":
		$filename = 'add_products-applications-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "c":
		$filename = 'add_products-applications-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'add_products-applications-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'add_products-applications-2000-2279.sql';
		$filetoread = 'Product_Spec_Migration-2000-2279.xlsx';
		break;	
		
}
unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	

include ('functions.php'); // contains pre-determined array of data fields for attributes
	
	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);
	$families_array = array();
	
	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				
				if($key == 0){	

					$products_families_array = explode(",",$field);
					foreach( $products_families_array as $family){
						$family = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $family));
						$families_array[] = $family;
					}
										
				}
			}

		print '</pre>';
	}
	
	$families_array_clean = array_unique($families_array);
	sort($families_array_clean);

	
	foreach($families_array_clean as $product_family){
		$insert_string = "INSERT INTO `pd_families_beta` (`Family`) VALUES ('".$product_family."');";
		$insert_string .= "\r\n";
				
		fwrite($handle, $insert_string);	
		$insert_string = "";
	}
	fclose($handle);

		/**
 * Case-insensitive in_array() wrapper.
 *
 * @param  mixed $needle   Value to seek.
 * @param  array $haystack Array to seek in.
 *
 * @return bool
 */
function in_arrayi($needle, $haystack)
{
	return in_array(strtolower($needle), array_map('strtolower', $haystack));
}