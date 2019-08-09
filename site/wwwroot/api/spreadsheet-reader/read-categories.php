<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'product-categories-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "b":
		$filename = 'product-categories-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "c":
		$filename = 'product-categories-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'product-categories-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'product-categories-2000-2279.sql';
		$filetoread = 'Product_Spec_Migration-2000-2279.xlsx';
		break;	
}
unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	

$category = "";

// If you need to parse XLS files, include php-excel-reader
//require('php-excel-reader/excel_reader2.php');

include ('functions.php'); // contains pre-determined array of data fields for attributes
	
	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);

	foreach ($spreadsheet_reader as $Row){
	
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				$category_name = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $field));
				// Product Category
				// Create insert string for each Category to be inserted into DB
				if( $key == 3){	
					switch($category_name){
						case "Laser":
							$category = "33";
							break;
						case "Components":
							$category = "23";
							break;
						case "Laser Measurement":
							$category = "43";
							break;
						case "Tools & Systems":
							$category = "53";
							break;
					}

					$insert_string = "INSERT INTO `pd_products_categories_beta` (`Product_ID`,`Category_ID`) VALUES ('".$record_id ."','".$category."');";
					$insert_string .= "\r\n";
					fwrite($handle, $insert_string);	
					$insert_string = "";
					
				}
				
			}
			
			// close file
			fclose($handle);		

		print '</pre>';
	}

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