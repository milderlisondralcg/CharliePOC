<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'add_product-categories-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "b":
		$filename = 'add_product-categories-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "c":
		$filename = 'add_product-categories-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'add_product-categories-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'add_product-categories-2000-2156.sql';
		$filetoread = 'Product_Spec_Migration-2000-2156.xlsx';
		break;	
}
unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	

include ('functions.php'); // contains pre-determined array of data fields for attributes

	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);

	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
		$record_id = $Row[0];
			foreach($Row as $key=>$field){
				
				if( $key == 3 && $field != ""){	

					if( $field == "Laser" || $field == "Lasers"){
						$category_id = array_search("Lasers",$categories_array);
					}elseif( $field == "Component" || $field == "Components" ){
						$category_id = array_search("Components",$categories_array);
					}elseif( $field == "Accessories & Services" ){
						$category_id = 13;
					}else{
						$category_id = array_search($field,$categories_array);
					}

					$insert_string = "INSERT INTO `pd_products_categories` (`Product_ID`,`Category_ID`) VALUES ('".$record_id."','".$category_id."');";
					//print $insert_string . "<br/>";
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