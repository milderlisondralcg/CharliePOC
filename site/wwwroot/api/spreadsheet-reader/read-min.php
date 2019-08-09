<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$set_to_read = $_GET['set'];
switch($set_to_read){
	case "A":
		$filename = 'add_products_attributes-1-499.sql';
		$filetoread = 'Product_Spec_Migration-1-499.xlsx';
		break;
	case "B":
		$filename = 'add_products_attributes-500-999.sql';
		$filetoread = 'Product_Spec_Migration-500-999.xlsx';
		break;	
	case "C":
		$filename = 'add_products_attributes-1000-1499.sql';
		$filetoread = 'Product_Spec_Migration-1000-1499.xlsx';
		break;	
	case "D":
		$filename = 'add_products_attributes-1500-1999.sql';
		$filetoread = 'Product_Spec_Migration-1500-1999.xlsx';
		break;	
	case "E":
		$filename = 'add_products_attributes-2000-2156.sql';
		$filetoread = 'Product_Spec_Migration-2000-2156.xlsx';
		break;			
}
//$filename = 'add_products_attributes.sql';
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
	
		//$product_name = $Row[1];
		//$insert_string = "INSERT into table (`ID`,`Name`) VALUES ('','".$product_name."')";
		//print $insert_string;
		//print '<br/>';
		$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);
			foreach($Row as $key=>$field){
				
				if($key <= 1454){
					
					
				$record_id = $Row[0];
				//print $key . " ";
				/*if($key >0 && $key <= 3){
					print $fields_array[$key] . ": ";
					print $field;
					print "<br/>";
				}
				*/
				//print $key . "<br/>";
				if( $key >= 4 ){
					$product_attribute = $fields_array[$key];
					if( $field != ""){
						$product_attribute_value = trim($field);
						$insert_string = "INSERT INTO `pd_products_attributes` (`Product_ID`,`Product_Attribute`,`Product_Attribute_Value`) VALUES ('".$record_id."','".$product_attribute."','".$product_attribute_value."');"; 
						$insert_string .= "\r\n";
						//print $insert_string . "<br/>";	
						
						fwrite($handle, $insert_string);	
						$insert_string = "";
					}

				}
	
				//if field key is 1 - this is the Product Name
				//$product_name = $Row[1];
				//$insert_string = "INSERT into table (`ID`,`Name`) VALUES ('','".$product_name."')";
				//print $insert_string;
				//print "<br/>";
				
				//if($key <= 1454){
					//if( trim($field) != ""){
						//$insert_string = "`" . trim($field) . "` text,";
						//print "\r\n";
						//print $insert_string;		
						//print trim($field);						
					//}

					//fwrite($handle, $insert_string);
					//print $field;
					//print "<br/>";
					//print "\r\n";					
				//}
				//print $insert_string;
				//fclose($handle);
				}
			}
			
			// close file
			fclose($handle);
			

		print '</pre>';
	}

	