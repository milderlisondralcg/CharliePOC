<?php 
/*
 * @Author: Universal Programming 
 * @Date: 2018-07-18
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2019-02-28
 * 
 */

require("D:/home/site/wwwroot/system/expressionengine/third_party/maps_updater/views/config.php");

$catList = array();
$proList = array(); 
$vendList = array();
$couList = array();
$aList = array();
$addList = array();
$pList = array();
$eList = array();
$wList = array();
$salesList = array();
$serList = array();
$priList = array();
$visList = array(); 

$traList = array();
$trainList = array();
$prodList = array();

$catTemp = "";
$proTemp = ""; 
$vendTemp = "";
$couTemp = "";
$aTemp = "";
$addTemp = "";
$pTemp = "";
$eTemp = "";
$wTemp = "";
$salesTemp = "";
$serTemp = "";
$priTemp = "";
$visTemp = "";

$traTemp = "";
$trainTemp = "";
$prodTemp = "";


if(isset($_POST["Location"]) && isset($_POST["Flag"])){
    getVendor($_POST["Location"], is_defined($_POST["Categories"]), is_defined($_POST["Products"]),$_POST["Flag"]);
}

function is_defined(&$var){
    return $var;
}
function getVendor($cou, $cat = NULL, $pro = NULL, $type = "All"){
    
    if(is_null($cat) && is_null($pro)){
        $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%"');
    }
    elseif(!is_null($cat) && is_null($pro)){
        $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%" AND categories LIKE "%'.$cat.'%"');
    }
    elseif(!is_null($cat) && !is_null($pro)){
        if($cat == "" && $type != "Training"){
            $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%" AND products LIKE "%'.$pro.'%"' );
        }
        elseif($cat == "" && $type == "Training"){
            $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%" AND trainings LIKE "%'.$pro.'%"' );
        }else{
            if($type == "Training"||$cat == "Training"){
                $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%" AND categories LIKE "%'.$cat.'%" AND trainings LIKE "%'.$pro.'%"' );
            }
            else{
                $result = $GLOBALS["mysqli"]->query('SELECT * FROM maps_providers WHERE countries LIKE "%'.$cou.'%" AND categories LIKE "%'.$cat.'%" AND products LIKE "%'.$pro.'%"' );
            }
        }
    }
    if($cat == "Select a Category"){
        return getVendor($cou, NULL, NULL, $type);
    }
    if($pro == "Select a Product") {
        return getVendor($cou, $cat, NULL, $type);
    }
    
    $getabr = $GLOBALS["mysqli"]->query("SELECT abr FROM maps_state WHERE name LIKE '%".$cou."%' LIMIT 1");
    $abr = "";
    $row2 = mysqli_fetch_assoc($getabr);
    $abr = $row2["abr"];
    $sendData["abbr"] = $abr;
    while($row = $result->fetch_assoc()){

        $GLOBALS["catTemp"] = explode(",!%%!,", $row["categories"]);
        if(strpos($row["categories"],$type)!== FALSE || $type == "All"){
            foreach($GLOBALS["catTemp"] as $value){
                if($value != ""){
                    array_push($GLOBALS["catList"], $value);
                }
            }
        }
        if($type == "Training" || $cat == "Training"){
            $GLOBALS["proTemp"] = explode(",!%%!,", $row["trainings"]);
            
            $GLOBALS["traTemp"] = explode(",!%%!,", $row["trainings"]);
            $GLOBALS["trainTemp"] = explode(",!%%!,", $row["trainings"]);
        }
        else{
            $GLOBALS["proTemp"] = explode(",!%%!,", $row["products"]);
            
            $GLOBALS["prodTemp"] = explode(",!%%!,", $row["products"]);
        }
        
        foreach($GLOBALS["proTemp"] as $value){
            if($value != ""){
               array_push($GLOBALS["proList"], $value);
            }
            sort($GLOBALS["proList"]);
        }
        
        foreach($GLOBALS["trainTemp"] as $value){
            if($value != ""){
               array_push($GLOBALS["trainList"], $value);
            }
        }
        
        $GLOBALS["vendTemp"] = $row["title"];
        $GLOBALS["couTemp"] = str_replace(",!%%!,", ",", $row["countries"]);
        $GLOBALS["aTemp"] = $row["address"];
        $GLOBALS["addTemp"] = $row["additional"];
        $GLOBALS["pTemp"] = $row["phone"];
        $GLOBALS["eTemp"] = $row["email"];
        $GLOBALS["wTemp"] = $row["website"];
        $GLOBALS["salesTemp"] = $row["sales"];
        $GLOBALS["serTemp"] = $row["service"];
        $GLOBALS["priTemp"] = $row["priority"];
        $GLOBALS["visTemp"] = $row["visible"];
        
        
        $GLOBALS["traTemp"] = str_replace(",!%%!,", ",", $row["trainings"]);
        $GLOBALS["prodTemp"] = str_replace(",!%%!,", ",", $row["products"]);

        if(strpos(implode($GLOBALS["catTemp"]),$type)!==FALSE || $type == "All"){
            array_push($GLOBALS["vendList"],$GLOBALS["vendTemp"]);
            array_push($GLOBALS["couList"],$GLOBALS["couTemp"]);
            array_push($GLOBALS["aList"],$GLOBALS["aTemp"]);
            array_push($GLOBALS["addList"],$GLOBALS["addTemp"]);
            array_push($GLOBALS["pList"],$GLOBALS["pTemp"]);
            array_push($GLOBALS["eList"],$GLOBALS["eTemp"]);
            array_push($GLOBALS["wList"],$GLOBALS["wTemp"]);
            array_push($GLOBALS["salesList"],$GLOBALS["salesTemp"]);
            array_push($GLOBALS["serList"],$GLOBALS["serTemp"]);
            array_push($GLOBALS["priList"],$GLOBALS["priTemp"]);
            array_push($GLOBALS["visList"],$GLOBALS["visTemp"]);
            
            array_push($GLOBALS["traList"],$GLOBALS["traTemp"]);
            array_push($GLOBALS["prodList"],$GLOBALS["prodTemp"]);
        }
    }
    
    if(empty($GLOBALS["catList"])){
        $sendData['categories'] = "";
    }else{
        if($type == "Training"){
            $sendData['categories'] = array($type);
            $sendData['training'] = array_values(array_unique($GLOBALS["trainList"]));
        }else{
            $sendData['categories'] = array_values(array_unique($GLOBALS["catList"]));
        }
    }
    
    if(empty($GLOBALS["proList"])){
        $sendData['products'] = "";
    }else{
        $sendData['products'] = array_values(array_unique($GLOBALS["proList"]));
    }
    
    $content["title"] = $GLOBALS["vendList"];
    $content["countries"] = $GLOBALS["couList"];
    $content["address"] = $GLOBALS["aList"];
    $content["additional"] = $GLOBALS["addList"];
    $content["phone"] = $GLOBALS["pList"];
    $content["email"] = $GLOBALS["eList"];
    $content["website"] = $GLOBALS["wList"];
    $content["sales"] = $GLOBALS["salesList"];
    $content["service"] = $GLOBALS["serList"];
    $content["priority"] = $GLOBALS["priList"];
    $content["visible"] = $GLOBALS["visList"];
    
    $content["trainings"] = $GLOBALS["traList"];
    $content["products"] = $GLOBALS["prodList"];
    
    $sendData["vendors"] = compileContent($content);
    
    print(json_encode($sendData, JSON_PRETTY_PRINT));
    $GLOBALS["mysqli"]->close();
}

function compileContent($content){
    $outArray = array();
    $out = "";
    for($int = 0; $int < count($content["title"]); $int++){
        $title = ""; $address = ""; $phone = ""; $email = ""; $website = ""; $additional = ""; $countries = ""; $priority = ""; $products = ""; $div = ""; $trainings = "";
        foreach($content as $key => $value){
            if($key == "title" && $value[$int] != ""){
                $title = "<div class='contact_vendor'><span class='vendor_title'>".$value[$int]."</span>";
            }
            if($key == "address" && $value[$int] != ""){
                $address = "<span class='vendor_address'>".$value[$int]."</span>";
            }
            if($key == "phone" && $value[$int] != ""){
                $phone = "<span class='vendor_phone'>Tel: ".$value[$int]."</span>";
            }
            if($key == "email" && $value[$int] != ""){
                $email = "<span class='vendor_email'><a href='mailto:".$value[$int]."'>".$value[$int]."</a></span>";
            }
            if($key == "website" && $value[$int] != ""){
                $website = "<span class='vendor_website'><a href='".$value[$int]."' target='_blank'>".$value[$int]."</a></span>";
            }
            if($key == "additional" && $value[$int] != ""){
                $additional = "<span class='vendor_additional'>".$value[$int]."</span>";
            }
            if($key == "countries" && $value[$int] != ""){
                $countries = "<span class='vendor_supported'><strong>Locations Supported:</strong> ". str_replace(",", ", ", $value[$int])."</span><br>";
            }
            if($key == "products" && $value[$int] != ""){
                $products = "<span class='vendor_products'><strong>Products Supported:</strong> " . str_replace(",", ", ", $value[$int]). "</span><br><br>";
            }
            if($key == "trainings" && $value[$int] != ""){
                $trainings = "<span class='vendor_training'><strong>Trainings:</strong> " . str_replace(",", ", ", $value[$int]). "</span>";
            }
            if($key == "priority" && $value[$int] != ""){
                $priority = $value[$int];
            }
        }
        $div = "</div>";
        $outArray[$title . $address . $phone . $email . $website . $additional . $countries . $products . $trainings . $div] = $priority;
    }
    asort($outArray);
    $i = 0;
    $len = count($outArray);
    foreach($outArray as $key => $value){
        if($i == $len - 1){
            $out = $out . $key;
        }else{
            $out = $out . $key;
        }
        $i++;
    }
    return $out;
}

?>