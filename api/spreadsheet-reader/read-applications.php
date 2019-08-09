<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch($set_to_read){
	case "A":
		$filename = 'add_products-applications-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "B":
		$filename = 'add_products-applications-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "C":
		$filename = 'add_products-applications-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "D":
		$filename = 'add_products-applications-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "E":
		$filename = 'add_products-applications-2000-2156.sql';
		$filetoread = 'Product_Spec_Migration-2000-2156.xlsx';
		break;	
	case "F":
		$filename = 'add_products-families-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "G":
		$filename = 'add_products-families-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "H":
		$filename = 'add_products-families-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "I":
		$filename = 'add_products-families-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "j":
		$filename = 'add_products-families-2000-2156.sql';
		$filetoread = 'Product_Spec_Migration-2000-2156.xlsx';
		break;		
}
unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	


// If you need to parse XLS files, include php-excel-reader
//require('php-excel-reader/excel_reader2.php');

include ('functions.php'); // contains pre-determined array of data fields for attributes
	
	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);

	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				/*
				if($key == 1439){	

					$product_application_array = explode(",",$field);
					
					foreach($product_application_array as $value){
						if( $value != ""){	

						// look for the id in $applications_array
						$prod_application = trim(array_search(trim($value),$applications_array));
							if( $prod_application!= ""){
								$insert_string = "INSERT INTO `pd_products_applications` (`Product_ID`,`Product_Application_ID`) VALUES ('".$record_id."','".$prod_application."');";
								$insert_string .= "\r\n";
								
								fwrite($handle, $insert_string);	
								$insert_string = "";							
							}
						}						
					}					
				}
				*/
				// Product Family
				/*
				if( $key == 2 && $field != ""){	
					
					$insert_string = "INSERT INTO `pd_families` (`ID`,`Family`) VALUES ('','".trim($field)."');";
					$insert_string .= "\r\n";
					fwrite($handle, $insert_string);	
					$insert_string = "";
					
				}*/
				
				// Get Family from the data and insert into products_families reference table
				if( $key == 2 && $field != ""){	
					$prod_family_id = trim(array_search(trim($field),$families_array));
					$insert_string = "INSERT INTO `pd_products_families` (`Product_ID`,`Family_ID`) VALUES ('".$record_id."','".$prod_family_id."');";
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