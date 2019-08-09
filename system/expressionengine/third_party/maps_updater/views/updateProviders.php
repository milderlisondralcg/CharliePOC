<?php require("config.php"); error_reporting(E_ALL);
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
    $id = $_POST["id"];
    $title = $_POST["title"];
    $check = $mysqli->query("SELECT * FROM maps_providers WHERE title='".$title."'");
    $check2 = $mysqli->query("SELECT * FROM maps_providers WHERE id=$id");
    $row = $check2->fetch_assoc();
    if($check->num_rows > 0 && $row["title"] != $title){
        printf("Please choose a different title.");
    }else{
        $address = $_POST["address"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $website = $_POST["website"];
        $countries = $_POST["countries"];
        $additional = str_replace('"', '\'', $_POST["additional"]);
        $categories = $_POST["categories"];
        $products = $_POST["products"];
        $trainings = $_POST["trainings"];
        $sales = $_POST["sales"];
        $service = $_POST["service"];
        $priority = $_POST["priority"];
        $visible = $_POST["visible"];
		$sales_phone = $_POST["sales_phone"];
		$sales_email = $_POST["sales_email"];
		$service_phone = $_POST["service_phone"];
		$service_email = $_POST["service_email"];
		$training_phone = $_POST["training_phone"];
		$training_email = $_POST["training_email"];		
        $stmt = $mysqli->prepare("UPDATE maps_providers SET title = ?, address = ?, phone = ?, email = ?, website = ?, countries = ?, additional = ?, categories = ?, products = ?, trainings = ?, sales = ?, service = ?, priority = ?, visible = ?, sales_phone = ?, sales_email = ?, service_phone = ?, service_email = ?, training_phone = ?, training_email = ? WHERE id = ?");
        $stmt->bind_param('ssssssssssiiisssssssi', $title, $address, $phone, $email, $website, $countries, $additional, $categories, $products, $trainings, $sales, $service, $priority, $visible, $sales_phone, $sales_email, $service_phone, $service_email, $training_phone, $training_email, $id);
        $stmt->execute();
    }
}
if(isset($_POST['insert'])){
    $title = $_POST["title"];
    $check = $mysqli->query("SELECT * FROM maps_providers WHERE title='".$title."'");
    if (!$check)
    {
        die('Error');
    }
    if($check->num_rows > 0){
        printf("Please choose a different title.");
    }else{
        $address = $_POST["address"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $website = $_POST["website"];
        $countries = $_POST["countries"];
        $additional = str_replace('"', '\'', $_POST["additional"]);
        $categories = $_POST["categories"];
        $products = $_POST["products"];
        $trainings = $_POST["trainings"];
        $sales = $_POST["sales"];
        $service = $_POST["service"];
        $priority = $_POST["priority"];
        $visible = $_POST["visible"];
        $stmt = $mysqli->prepare("INSERT INTO maps_providers (title, address, phone, email, website, countries, additional, categories, products, trainings, sales, service, priority, visible) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssssssiiii', $title, $address, $phone, $email, $website, $countries, $additional, $categories, $products, $trainings, $sales, $service, $priority, $visible);
        $stmt->execute();
        printf($mysqli->insert_id);
    }
}
if(isset($_POST["delete"])){
    $check = $mysqli->query("SELECT id FROM maps_providers");
    $n = $check->num_rows;
    $id = $_POST["id"];
    if($n == 1){
        $mysqli->query("DELETE FROM maps_providers WHERE id = $id");
        $mysqli->query("ALTER TABLE maps_providers DROP id");
        $mysqli->query("ALTER TABLE maps_providers ADD id int NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY(id)");
        
    }else{
        $mysqli->query("DELETE FROM maps_providers WHERE id = $id");
    }
};

$mysqli->close();
?>