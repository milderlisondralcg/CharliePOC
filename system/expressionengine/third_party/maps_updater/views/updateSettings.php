<?php require("config.php"); error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
$mysqli = new mysqli($connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword, $connectstr_dbname);
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if(isset($_POST['update'])){
    $name = $_POST["name"];
    $val = $_POST["value"];
    $mysqli->query("UPDATE maps_settings SET value = '$val' WHERE name = '$name'");
};
$mysqli->close();
?>
