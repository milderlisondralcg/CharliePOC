<?php 
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
    //Paths
    $thirdparty = "D:/home/site/wwwroot/system/expressionengine/third_party/";
    if($thirdparty){                                                                  //Expression Engine 2 third party path
        $fullpath = $thirdparty;
        $views = str_replace("D:/home/site/wwwroot", "", $fullpath);
        $views = $views . "simplemaps_updater/views/";
        $home =  str_replace("D:/home/site/wwwroot", "", $fullpath);
        $home = $home . "contact_us/";
    }else{                                                                          //Variable for paths outside Expression Engine
        $fullpath;
        $views;
        $home;
    }

    //Google API KEY
    $GLOBALS['GAPI'] = "AIzaSyBMYCSxNufdoORnOlTOQTSY-fdRrzTzWaI";

    //Database
    $connectstr_dbhost = '';
    $connectstr_dbname = '';
    $connectstr_dbusername = '';
    $connectstr_dbpassword = '';

    foreach ($_SERVER as $key => $value) {
        if (strpos($key, "MYSQLCONNSTR_localdb") !== 0) {
            continue;
        }
        
        $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
        $connectstr_dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
        $connectstr_dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
        $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
    }

    //Database Connect
    $mysqli = new mysqli($connectstr_dbhost, $connectstr_dbusername, $connectstr_dbpassword, $connectstr_dbname);
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $settings = $mysqli->query("SELECT * FROM simplemaps_settings");

    //Simplemaps
    $mapspath = "D:/home/site/wwwroot/assets/js/simplemaps/";                       //Simplemaps Javascript Folder
    $mapspathforjs = str_replace("D:/home/site/wwwroot", ".", $mapspath);
    $str = file_get_contents($mapspath . 'mapdata.js');
    //$str = $mysqli->query("SELECT * FROM simplemaps_location FOR JSON");
    $str = str_replace("var simplemaps_worldmap_mapdata=", "", $str);
    $str = str_replace("};", "}", $str);
    $array = json_decode($str);
?>