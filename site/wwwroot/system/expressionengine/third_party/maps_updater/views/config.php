<?php error_reporting(E_ALL);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
    /*
    Section: Path variables
        @Variables:
            $thirdparty: Path to the directory containing the maps_updater module
            $mapspath: Path to directory containing Simple Maps javascript files
            $mapsupdaterpath: Path to the maps_updater folder
            $hiddenpath: Path hidden from the client
    */
    $toolspath = "https://charlie.coherent.com/api/?action=get-admin-products-category&category=tools_systems";
    $lmcpath = "https://charlie.coherent.com/api/?action=get-admin-products-category&category=lmc";
    $componentspath = "https://charlie.coherent.com/api/?action=get-admin-products-category&category=components";
    $laserspath = "https://charlie.coherent.com/api/?action=get-admin-products-category&category=lasers";
    $thirdparty = "D:/home/site/wwwroot/system/expressionengine/third_party/";
    $mapspath = "D:/home/site/wwwroot/assets/js/simplemaps/";
    $contactselectorpath = "D:/home/site/wwwroot/system/expressionengine/third_party/maps_updater/contact_selector/";
    $hiddenpath = "D:/home/site/wwwroot";
    $selector_API_path = "D:/home/site/wwwroot/assets_api_inc/contact_us/";
    $selector_template_path = "D:/home/site/wwwroot/system/expressionengine/templates/default_site/includes.group/";
    $selector_selectize_js_path = "D:/home/site/wwwroot/assets/js/contact_us/";
    $selector_selectize_css_path = "D:/home/site/wwwroot/assets/css/contact_us/";
    
    if($thirdparty){                                                                
        $fullpath = $thirdparty;
        $views = str_replace($hiddenpath, "", $fullpath);
        $views = $views . "maps_updater/views/";
        $home =  str_replace($hiddenpath, "", $fullpath);
        $home = $home . "maps_updater/";
    }else{                                                                         
        $fullpath;
        $views;
        $home;
    }

    //To Allow Standalone change to true. Do not set to true if using inside Expression Engine.
    $allow_standalone = false;

    //Google API KEY
    $GLOBALS['GAPI'] = "AIzaSyCbaoiVFx-ejkbCOlJq8gwnUKC5es0GRSE";

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

    //Logic for modifying paths
    $mapspathforjs = str_replace($hiddenpath, ".", $mapspath);
    $str = file_get_contents($mapspath . 'mapdata.js');
    $str = str_replace("var simplemaps_worldmap_mapdata=", "", $str);
    $str = str_replace("};", "}", $str);
    $array = json_decode($str);

    //Moving template and api on init
    $selector_template = "contact_selector.html";
    $selector_api = "handle_contact_selector.php";
    $selectize_js_file = "selectize.js";
    $selectize_css_file = "selectize.css";
    $region_js = "regions.js";
    if(!file_exists($selector_API_path . $selector_api)){
        if(!file_exists($selector_API_path)){
            mkdir($selector_API_path, 0777, true);
        }
        copy($contactselectorpath.$selector_api, $selector_API_path.$selector_api);
    }
    if(!file_exists($selector_template_path . $selector_template)){
        if(!file_exists($selector_template_path)){
            mkdir($selector_template_path, 0777, true);
        }
        copy($contactselectorpath.$selector_template, $selector_template_path.$selector_template);
    }
    if(!file_exists($selector_selectize_js_path . $selectize_js_file)){
        if(!file_exists($selector_selectize_js_path)){
            mkdir($selector_selectize_js_path, 0777, true);
        }
        copy($contactselectorpath.$selectize_js_file, $selector_selectize_js_path.$selectize_js_file);
    }
    if(!file_exists($selector_selectize_css_path . $selectize_css_file)){
        if(!file_exists($selector_selectize_css_path)){
            mkdir($selector_selectize_css_path, 0777, true);
        }
        copy($contactselectorpath.$selectize_css_file, $selector_selectize_css_path.$selectize_css_file);
    }
    if(!file_exists($selector_selectize_js_path . $region_js)){
        if(!file_exists($selector_selectize_js_path)){
            mkdir($selector_selectize_js_path, 0777, true);
        }
        copy($contactselectorpath.$region_js, $selector_selectize_js_path.$region_js);
    }
    
    
    // DO NOT MODIFY BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING
    //---------------------------------------------------------------------


    //Database Table Creation
    
    $t_temp;
    $a_temp;
    $d_temp;
    $c_temp;
    if(!$mysqli->query("SELECT * FROM maps_state")){
        $mysqli->query("CREATE TABLE IF NOT EXISTS maps_state (abr VARCHAR(2) NOT NULL,name VARCHAR(120) NOT NULL,zoomable ENUM('yes','no'),inactive ENUM('yes','no'),color VARCHAR(7),content TEXT, PRIMARY KEY(abr))");
        foreach($array->state_specific as $key => $value){      
            $i_temp;
            $z_temp;  
            $stmt = $mysqli->prepare("INSERT INTO maps_state (abr,name,zoomable,inactive,color,content) VALUES(?,?,?,?,?,?)");
            if(!isset($value->inactive)){
                $i_temp = "no";
            }else{
                $i_temp = $value->inactive;
            }
            if(!isset($value->zoomable)){
                $z_temp = "no";
            }else{
                $z_temp = $value->zoomable;
            }
            $n_temp = mb_convert_encoding($value->name, "HTML-ENTITIES", "UTF-8");
            $stmt->bind_param('ssssss', $key, $n_temp, $z_temp, $i_temp, $value->color, $content);
            $stmt->execute();
        }
    }
    if(!$mysqli->query("SELECT * FROM maps_settings")){
        $mysqli->query("CREATE TABLE IF NOT EXISTS maps_settings (name VARCHAR(120) NOT NULL, value TEXT, PRIMARY KEY(name))");
        foreach($array->main_settings as $key => $value){
            $stmt = $mysqli->prepare("INSERT INTO maps_settings (name, value) VALUES (?, ?)");
            $stmt->bind_param('ss', $key, $value);
            $stmt->execute();
        }
    }
    $mysqli->query("CREATE TABLE IF NOT EXISTS maps_providers (id int NOT NULL AUTO_INCREMENT, title VARCHAR(150) NOT NULL, address TEXT, phone VARCHAR(100), email VARCHAR(254), website VARCHAR(256), countries TEXT, additional TEXT, categories TEXT, products TEXT, trainings TEXT, sales BIT(1), service BIT(1), priority TINYINT(4), visible BIT(1), PRIMARY KEY(id))");
    $mysqli->query("CREATE TABLE IF NOT EXISTS maps_categories (id int NOT NULL AUTO_INCREMENT, category VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
    $mysqli->query("CREATE TABLE IF NOT EXISTS maps_products (id int NOT NULL AUTO_INCREMENT, product VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
    $mysqli->query("CREATE TABLE IF NOT EXISTS maps_custom_products (id int NOT NULL AUTO_INCREMENT, product VARCHAR(256) NOT NULL, PRIMARY KEY(id))");
    $mysqli->query("CREATE TABLE IF NOT EXISTS maps_location (id int NOT NULL AUTO_INCREMENT,lat DOUBLE(10,8), lng DOUBLE(11,8), name VARCHAR(45), content TEXT, PRIMARY KEY(id))");
?>