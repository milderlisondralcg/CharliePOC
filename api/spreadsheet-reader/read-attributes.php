	<?php
// Read attributes from Excel and create SQL file with insert statements
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$insert_string = "";

$set_to_read = $_GET['set'];
switch(strtolower($set_to_read)){
	case "a":
		$filename = 'add_products_attributes-1-499.sql';
		$filetoread = 'products-1-499.xlsx';
		break;
	case "b":
		$filename = 'add_products_attributes-500-999.sql';
		$filetoread = 'products-500-999.xlsx';
		break;	
	case "c":
		$filename = 'add_products_attributes-1000-1499.sql';
		$filetoread = 'products-1000-1499.xlsx';
		break;	
	case "d":
		$filename = 'add_products_attributes-1500-1999.sql';
		$filetoread = 'products-1500-1999.xlsx';
		break;	
	case "e":
		$filename = 'add_products_attributes-2000-2279.sql';
		$filetoread = 'products-2000-2279.xlsx';
		break;
	case "f":
		$filename = 'add_products_attributes-1-199.sql';
		$filetoread = 'products-1-199.xlsx';
		break;	
	case "g":
		$filename = 'add_products_attributes-200-499.sql';
		$filetoread = 'Product_Spec_Migration-200-499.xlsx';
		break;
	case "h":
		$filename = 'add_products_attributes-500-699.sql';
		$filetoread = 'Product_Spec_Migration-500-699.xlsx';
		break;		
	case "i":
		$filename = 'add_products_attributes-700-999.sql';
		$filetoread = 'Product_Spec_Migration-700-999.xlsx';
		break;		
	case "j":
		$filename = 'add_products_attributes-1000-1199.sql';
		$filetoread = 'Product_Spec_Migration-1000-1199.xlsx';
		break;
	case "k":
		$filename = 'add_products_attributes-1200-1499.sql';
		$filetoread = 'Product_Spec_Migration-1200-1499.xlsx';
		break;		
}

unlink($filename);
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);	


// If you need to parse XLS files, include php-excel-reader
//require('php-excel-reader/excel_reader2.php');

include ('functions.php'); // contains pre-determined array of data fields for attributes
	
	require('SpreadsheetReader.php');

	$spreadsheet_reader = new SpreadsheetReader($filetoread);
	$insert_string .= "INSERT INTO `pd_products_attributes_beta` (`Product_ID`,`Product_Attribute`,`Product_Attribute_Value`) VALUES ";
	
	foreach ($spreadsheet_reader as $Row){
		print '<pre>';
		
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				
				if($key <= 1506){
					
					$record_id = $Row[0];
				
					if( $key >= 4 ){
						$product_attribute = $fields_array[$key]; // $fields_array contains the column names from the original Excel file
						if( $field != ""){
							$product_attribute_value = trim($field);
							//$insert_string = "INSERT INTO `pd_products_attributes_beta` (`Product_ID`,`Product_Attribute`,`Product_Attribute_Value`) VALUES ('".$record_id."','".$product_attribute."','".$product_attribute_value."');"; 
							$insert_string .= "('".$record_id."','".$product_attribute."','".$product_attribute_value."'),"; 
							$insert_string .= "\r\n";
							
							//fwrite($handle, $insert_string);	
							//$insert_string = "";
						}
					}
				}
			}

			// close file
			//fclose($handle);			

		print '</pre>';
	}
	
	$insert_string = trim($insert_string);	
			$clean_insert_string = substr($insert_string, 0, (strlen($insert_string) - 1)) . ";";
			fwrite($handle, $clean_insert_string);
			// close file
			fclose($handle);

	