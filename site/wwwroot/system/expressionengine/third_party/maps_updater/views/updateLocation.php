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
$pdo_conn = new PDO("mysql:host=" . $connectstr_dbhost . ";dbname=" . $connectstr_dbname, $connectstr_dbusername, $connectstr_dbpassword);
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if(isset($_POST['update'])){
    $abbrev = $_POST["abbr"];
    $name = $_POST["name"];
    $inactive = $_POST["inactive"];
    $content = $_POST["content"];
    $zoom = $_POST["zoomable"];
    $color = $_POST["color"];
	$border = $_POST["border"];
    
    if($zoom=="true"){$z_temp="yes";}else{$z_temp="no";}
    if($inactive=="true"){$i_temp="yes";}else{$i_temp="no";}
    $stmt = $mysqli->prepare("UPDATE maps_state SET abr = ?, name = ?, content = ?, inactive = ?, zoomable = ?, color = ?, border=? WHERE abr = ?");
    $stmt->bind_param('sssssss', $abbrev, $name, $content, $i_temp, $z_temp, $color, $border, $abbrev);
    $stmt->execute();
	
	
$pdo_stmt = $pdo_conn->prepare("UPDATE `maps_state` SET `border`=:border WHERE `abr`=:abbrev");
$pdo_stmt->bindValue(':abbrev',$abbrev, PDO::PARAM_STR);
$pdo_stmt->bindValue(':border',$border, PDO::PARAM_STR);
$pdo_stmt->execute();
	
};
$mysqli->close();
?>