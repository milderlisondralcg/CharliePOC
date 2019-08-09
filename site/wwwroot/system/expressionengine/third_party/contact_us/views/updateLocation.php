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

if(isset($_POST['state_name'])){
    $abbrev = $_POST["state_abbr"];
    $name = $_POST["state_name"];
    $inactive = $_POST["state_inactive"];
    $title = $_POST["state_title"];
    $desc = $_POST["state_description"];
    $addtl = $_POST["state_additional"];
    $zoom = $_POST["state_zoomable"];
    $color = $_POST["state_color"];
    
    foreach($_POST["state_update"] as $key => $value){
        if($value=="true"){
            if($zoom[$key]=="true"){$z_temp="yes";}else{$z_temp="no";}
            if($inactive[$key]=="true"){$i_temp="yes";}else{$i_temp="no";}
            $stmt = $mysqli->prepare("UPDATE simplemaps_state SET abr = ?, name = ?, description = ?, inactive = ?, zoomable = ?, color = ?, title = ?, additional = ? WHERE abr = ?");
            $stmt->bind_param('sssssssss', $abbrev[$key], $name[$key], $desc[$key], $i_temp, $z_temp, $color[$key],$title[$key],$addtl[$key], $abbrev[$key]);
            $stmt->execute();
            /*if(!$mysqli->query("UPDATE simplemaps_state SET abr = '$abbrev[$key]', name =$name[$key], description = '$desc[$key]', inactive = '$i_temp', zoomable = '$z_temp', color = '$color[$key]' WHERE abr = '$abbrev[$key]'")){
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array('message' => $mysqli->error));
            }else{
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(array('message' => $mysqli->insert_id));
            }*/
        };
    };
};
?>