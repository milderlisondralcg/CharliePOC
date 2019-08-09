<?php require("config.php"); error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */

$mysqli = new mysqli($connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword, $connectstr_dbname);
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 
if(isset($_POST['delete'])){
    $check = $mysqli->query("SELECT id FROM maps_location");
    $n = $check->num_rows;
    $id = $_POST['index'];
    if($n == 1){
        $mysqli->query("DELETE FROM maps_location WHERE id = $id");
        $mysqli->query("ALTER TABLE maps_location DROP id");
        $mysqli->query("ALTER TABLE maps_location ADD id int NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(id)");
    }else{
        $mysqli->query("DELETE FROM maps_location WHERE id = $id");
    }
}
if(isset($_POST['update'])){
	extract($_POST);
    $id = $_POST['index'];
    $name = $_POST["name"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $content = $_POST["content"];
	//$border = $border;
    if(!$mysqli->query("UPDATE maps_location SET lat = $lat, lng = $lng, name = '$name', content = '$content',`border_color`='$border_color', `location_color`='$location_color',border = '$border',`hover_color`='$hover_color' WHERE id = $id")){
        print_r($mysqli->error);
    }
    
}

if(isset($_POST['update_group'])){
	extract($_POST);
	switch($group){
		case "0":		
			break;
		case "1":
			$image_url = '/assets/site_images/orange-diamond1.png';
			break;
		case "2":
			$image_url = '/assets/site_images/green-triangle1.png';
			break;
		case "3":
			$image_url = '/assets/site_images/red-box1.png';
			break;
		case "4":
			$image_url = '/assets/site_images/yellow-triangle1.png';
			break;
	}

    if(!$mysqli->query("UPDATE maps_location SET `group` = '$group', `image_url`='$image_url', `type`='image' WHERE id = $id")){
        print_r($mysqli->error);
    }
    
}

if(isset($_POST['insert'])){
    $name = $_POST["name"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    $content = $_POST["content"];
    $stmt = $mysqli->prepare("INSERT INTO maps_location (lat,lng,name,content) VALUES(?,?,?,?)");
    $stmt->bind_param('ddss', $lat, $lng, $name, $content);
    $stmt->execute();
    printf($mysqli->insert_id);
}
$mysqli->close();
?>