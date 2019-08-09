<?php

// Creates a SQL file to insert data into pd_products_general
// The product id is derived from a corresponding field within the Excel file
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$cdt = date("m-d-Y-H-i");
$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'add-products-1-499-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "b":
		$filename = 'add-products-500-999-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "c":
		$filename = 'add-products-1000-1499-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'add-products-1500-1999-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'add-products-2000-2279-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-2000-2279.xlsx';
		break;
	default:
		$filename = 'add-products-1-499-' . $cdt . '.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
		
}

$sql_directory = "sql";
$fullpath_sql_file = $sql_directory.'/'.$filename;
print $sql_directory;
unlink($sql_directory.'/'.$filename);
$handle = fopen($fullpath_sql_file, 'w') or die('Cannot open file:  '.$filename);

// If you need to parse XLS files, include php-excel-reader
//require('php-excel-reader/excel_reader2.php');

include ('functions.php'); // contains pre-determined array of data fields for attributes
require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);
print '<pre>';
	foreach ($spreadsheet_reader as $Row){	
				
		$product_id = $Row[0];
		$product_name = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $Row[1]));
		$acquired_site = addslashes(trim($Row[1492]));
		
		if( $product_name != ""){					
			$insert_string = "INSERT INTO `pd_products_general_beta` (`ID`,`Name`,`Acquired_Site`) VALUES ('".$product_id."','".$product_name."','".$acquired_site."');";
			$insert_string .= "\r\n";

			fwrite($handle, $insert_string);	
			$insert_string = "";
		}
		
	}

		// close file
		fclose($handle);
print '</pre>';
			
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