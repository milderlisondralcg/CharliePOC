<?php
session_start();
error_reporting(E_ERROR);
spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = '../models/';

    include $path.$className.'.php';
}

$logs = new Errors();
$all_data = array();
$log_data = $logs->get();
if($log_data != 0){
	foreach( $log_data as $log){
		extract($log);
		$data['Created'] = date("m/d/Y g:i A", strtotime($Created));
		$data['Admin'] = $Admin;
		$data['Action'] = $Action;
		$data['Object'] = $Object;
		$previous_data = json_decode($Previous_Data);
		foreach($previous_data as $key=>$value){
			$previous_data_string .= $key . ' : ' . $value . '<br/>';
		}
		$data['Previous Data'] = $previous_data_string;
		
		$updated_data = json_decode($Updated_Data);
		foreach($updated_data as $key=>$value){
			$updated_data_string .= $key . ' : ' . $value . '<br/>';
		}
		
		$data['Updated Data'] = $updated_data_string;
		
		$all_data[] = $data;
		
	}
	print json_encode(array("data"=>$all_data));
}