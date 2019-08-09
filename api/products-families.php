<?php
$data = array();
$db_host = "us-cdbr-azure-west-b.cleardb.com";
$db_name = "pocmarcomwwwcharliedb";
$db_user = "be34d90920856c";
$db_pass = "804e507c";

            $conn = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name, $db_user, $db_pass);
            $conn->exec("set names utf8");
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);