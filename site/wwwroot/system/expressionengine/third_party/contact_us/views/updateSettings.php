<?php require("config.php");
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
$mysqli = new mysqli($connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword, $connectstr_dbname);
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

if(isset($_POST['setting_name'])){
    $name = $_POST["setting_name"];
    $val = $_POST["setting_value"];
    foreach($_POST["setting_update"] as $key => $value){
        if($value=="true"){
            //$mysqli->query("INSERT INTO simplemaps_settings (name, value) VALUES('$name[$key]','$val[$key]')");
            if(!$mysqli->query("UPDATE simplemaps_settings SET value = '$val[$key]' WHERE name = '$name[$key]'")){
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array('message' => $mysqli->error));
            }else{
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array('message' => $mysqli->insert_id));
            }
        };
    };
};
?>
