<?php
// This is to populate the products_applications table
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'add_products-applications-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
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
			break;
	
}
$filetoread = "ProdDesignation_IDs_28AUG.xlsx";
$filename = 'jfj-designations-funds' . mktime(). '.sql';
unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	

$insert_string = "";
$insert_string = "INSERT INTO `designation_fund` (`fund_id`,`title`,`designation_id`) VALUES ";

include ('functions.php'); // contains pre-determined array of data fields for attributes
	
	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);

	foreach ($spreadsheet_reader as $data)
	{
		print '<pre>';
		print_r($data);
		$fund_id = $data[0];
		$designation_id = $data[1];
		$title = addslashes($data[4]);
		$insert_string .= "('".$fund_id."','".$title."','".$designation_id."'), ";
		$insert_string .= "\r\n";
								
		fwrite($handle, $insert_string);	
		$insert_string = "";


		print '</pre>';
	}
	
	$insert_string = trim($insert_string);	
	$clean_insert_string = substr($insert_string, 0, (strlen($insert_string) - 1)) . ";";
	fwrite($handle, $clean_insert_string);
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