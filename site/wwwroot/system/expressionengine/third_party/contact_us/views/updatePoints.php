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

if(isset($_POST['location_name'])){
    $name = $_POST["location_name"];
    $lat = $_POST["location_lat"];
    $lng = $_POST["location_lng"];
    $title = $_POST["location_title"];
    $desc = $_POST["location_description"];
    $add = $_POST["location_additional"];
    $countries = $_POST["location_countries"];
    foreach($_POST["location_update"] as $key => $value){
        if($value=="true"){
            $mysqli->query("UPDATE simplemaps_location SET lat = $lat[$key], lng = $lng[$key], name = '$name[$key]', description = '$desc[$key]', title = '$title[$key]', additional = '$add[$key]', countries = '$countries[$key]' WHERE id = $key + 1");
        };
    };
    $delTemp = 1;
    foreach($_POST["location_delete"] as $key => $value){
        if($value=="true"){
            $mysqli->query("DELETE FROM simplemaps_location WHERE id = $delTemp");
            $mysqli->query("ALTER TABLE simplemaps_location DROP id");
            $mysqli->query("ALTER TABLE simplemaps_location ADD id int NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(id)");
            $delTemp--;
        };
        $delTemp++;
    };
    foreach($_POST["location_new"] as $key => $value){
        if($value=="true"){
            $stmt = $mysqli->prepare("INSERT INTO simplemaps_location (lat,lng,name,description,title,additional,countries) VALUES(?,?,?,?,?,?,?)");
            $stmt->bind_param('ddsssss', $lat[$key], $lng[$key], $name[$key], $desc[$key],$title[$key],$add[$key],$countries[$key]);
            $stmt->execute();
        };
    };
};
?>