<?php

$hostname = "us-cdbr-azure-west-b.cleardb.com";
$username = "be34d90920856c";
$password = "804e507c";
$database = "pocmarcomwwwcharliedb";

$link = mysqli_connect($hostname, $username, $password, $database);
if (mysqli_connect_errno()) {
   die("Connect failed: %s\n" + mysqli_connect_error());
   exit();
}
else {
    echo "Success";
}
?>
