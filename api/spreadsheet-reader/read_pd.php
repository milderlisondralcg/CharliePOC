<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '-1');
set_time_limit(3600);

$applications_array = array();

$my_file = 'applications_.sql';
unlink($my_file);
$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);

	// If you need to parse XLS files, include php-excel-reader
	//require('php-excel-reader/excel_reader2.php');

	require('SpreadsheetReader.php');

	if($_GET['datatype'] == 'alt'){
		$spreadsheet_reader = new SpreadsheetReader('Product_Spec_Migration-alt1.xlsx');
	}elseif($_GET['datatype'] == 'SetA'){
		$spreadsheet_reader = new SpreadsheetReader('Product_Spec_Migration-SetA.xlsx');
	}elseif($_GET['datatype'] == 'SetB'){
		$spreadsheet_reader = new SpreadsheetReader('Product_Spec_Migration-SetB.xlsx');
	}elseif($_GET['datatype'] == 'fields'){
		$spreadsheet_reader = new SpreadsheetReader('Product_Spec_Migration-fields-only.xlsx');
	}else{		
		$spreadsheet_reader = new SpreadsheetReader('Product_Spec_Migration.xlsx');
	}
	//print gettype($spreadsheet_reader);
	
	foreach ($spreadsheet_reader as $Row)
	{
		print '<pre>';
		
		if($_GET['datatype'] == 'fields'){
			foreach($Row[0] as $key=>$field){
					//$filename = 'create_main.sql';
					//unlink($filename);
					//$handle = fopen($filename, 'a') or die('Cannot open file:  '.$filename);				
				if($key <= 1454){
					//$insert_string .= "`" . trim($field) . "` text,";
					print $field;
					print "<br/>";
					//print "\r\n";					
				}
				//print $insert_string;
				//fclose($handle);
			}
			//fwrite($handle, $insert_string);
		}elseif( ($_GET['datatype'] == 'alt') || ($_GET['datatype'] == 'SetA') || ($_GET['datatype'] == 'SetB') ){
			//foreach($Row as $key=>$attribute_value){
				// Applications fields only
				//if($key == 1439 && ( $attribute_value != "" )){
					if( $Row[1439] != ""){
						//print $Row[1439] . "<br/>";
						// explode the data because some records will have multiple applications listed separated by commas
						$temp_array = explode(",",$Row[1439]);
					
						foreach($temp_array as $key=>$temp_value){
							if( in_arrayi(trim($temp_value), $applications_array) == FALSE){
								$applications_array[] = trim($temp_value);
								$data = "INSERT INTO `pd_applications` (`ID`,`Title`) VALUES ('','" . trim($temp_value) . "');";
								$data .= "\r\n";
								fwrite($handle, $data);
								$data = "";
							}
						}
						unset($temp_array);						
					}				
			//}			

		}else{
			print_r($Row[1]);
		}
		print '</pre>';
	}
	
	print '<pre>';
	$applications_array_unique = array_unique($applications_array);
	sort($applications_array_unique);
	print_r($applications_array_unique);
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
	
	