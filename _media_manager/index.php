<?php
session_start();
if(!isset($_SESSION['username'])){
	header("Location: /_media_manager/noaccess.php");
}else{
	header("Location: /_media_manager/home.php");
}