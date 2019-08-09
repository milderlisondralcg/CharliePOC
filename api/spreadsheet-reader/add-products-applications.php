<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'add-products-applications-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "b":
		$filename = 'add-products-applications-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "c":
		$filename = 'add-products-applications-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'add-products-applications-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'add-products-applications-2000-2274.sql';
		$filetoread = 'Product_Spec_Migration-2000-2274.xlsx';
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
		$product_id = $Row[0];
		$product_name = addslashes(trim($Row[1]));
		$acquired_site = addslashes(trim($Row[1444]));
		
		//if( $product_name != ""){					
			//$insert_string = "INSERT INTO `pd_products_general` (`ID`,`Name`,`Acquired_Site`) VALUES ('".$product_id."','".$product_name."','".$acquired_site."');";
			//$insert_string .= "\r\n";
			//print $insert_string. "<br/>";
			//fwrite($handle, $insert_string);	
			//$insert_string = "";
		//}
						
			foreach($Row as $key=>$field){
				$record_id = $Row[0];
				
				
				if($key == 1483){	

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
				
				
				// Product Category
				// Create insert string for each Product to be inserted into DB
				$acquired_site = "";

				//print $acquired_site;

					
					/*
				if( $key == 1 || $key == 1444 ){
						$product_name = addslashes(trim($field));
						print $product_name . "<br/>";
					if( $key == 1444){ // get the Acquired Site info; this is the company the product originally came from
						$acquired_site = $field;
					}		
						print $acquired_site . "<br/>";
					if( $field != ""){					
						$insert_string = "INSERT INTO `pd_products_general` (`ID`,`Name`,`Acquired_Site`) VALUES ('','".$product_name."','".$acquired_site."');";
						$insert_string .= "\r\n";
						print $insert_string. "<br/>";
						fwrite($handle, $insert_string);	
						$insert_string = "";
					}
					
				}
				*/
				
				// Product Category
				// Create insert string for each Category to be inserted into DB
				/*
				if( $key == 3 && $field != ""){	
					
					$insert_string = "INSERT INTO `pd_categories` (`ID`,`Category`) VALUES ('','".trim($field)."');";
					$insert_string .= "\r\n";
					fwrite($handle, $insert_string);	
					$insert_string = "";
					
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
				/*
				if( $key == 2 && $field != ""){	
					$prod_family_id = trim(array_search(trim($field),$families_array));
					$insert_string = "INSERT INTO `pd_products_families` (`Product_ID`,`Family_ID`) VALUES ('".$record_id."','".$prod_family_id."');";
					//print $insert_string . "<br/>";
					$insert_string .= "\r\n";
					fwrite($handle, $insert_string);	
					$insert_string = "";
					
				}*/
				
			}
		print '</pre>';
	}

		// close file
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