<?php
$conn = null;
		foreach ($_SERVER as $key => $value) {
			if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
				continue;
			}
			
			$db_host = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
			$db_name = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
			$db_user = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
			$db_pass = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);	
		}
		
		try{
            $conn = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pass);
            $conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
		
		print_r($conn);