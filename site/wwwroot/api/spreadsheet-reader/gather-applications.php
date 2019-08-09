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

	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				
				if($key == 1487){	

					$product_application_array = explode(",",$field);
					
					foreach($product_application_array as $value){
						if( $value != ""){	

						// look for the id in $applications_array
						$prod_application = trim(array_search(trim($value),$applications_array));
							if( $prod_application!= ""){
								$insert_string = "INSERT INTO `pd_products_applications_beta` (`Product_ID`,`Product_Application_ID`) VALUES ('".$record_id."','".$prod_application."');";
								$insert_string .= "\r\n";
								
								fwrite($handle, $insert_string);	
								$insert_string = "";							
							}
						}						
					}					
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