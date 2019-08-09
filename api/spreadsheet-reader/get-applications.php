<?php
// This helps to populate the applications table

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		//$filename = 'applications-1-499.sql';
		$filename = 'applications.sql';
		//$filetoread = 'Product_Spec_Migration-1-200.xlsx';
		$filetoread = 'Applications-Only.xlsx';
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
	$applications_array = array();
	
	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				
				if($key == 0){	

					$products_application_array = explode(",",$field);
					foreach( $products_application_array as $application){
						$application = trim(preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $application));
					
						if($application != "" || $application != 'industrial and medical' || $application != 'etc.' || $application != 'Time-resolved Spectroscopy' || $application = 'Thin-film Scribing'){
							$applications_array[] = $application;
						}
						/*
						$insert_string = "INSERT INTO `pd_applications_beta` (`Title`) VALUES ('".$application."');";
						$insert_string .= "\r\n";
								
						fwrite($handle, $insert_string);	
						$insert_string = "";
						*/
					}
										
				}
			}

		print '</pre>';
	}
	
	$applications_array_clean = array_unique($applications_array);
	sort($applications_array_clean);

	
	foreach($applications_array_clean as $application){
		if($application != "" || $application != 'industrial and medical' || $application != 'etc.' || $application != 'Time-resolved Spectroscopy' || $application = 'Thin-film Scribing'){
			$insert_string = "INSERT INTO `pd_applications_beta` (`Title`) VALUES ('".$application."');";
			$insert_string .= "\r\n";
					
			fwrite($handle, $insert_string);	
			$insert_string = "";
		}
	}
	fclose($handle);
	
	print '<pre>';
print_r($applications_array_clean);
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