<?php 
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
    function get_string_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);   
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }
    function strposnth($haystack, $needle, $nth=1, $insenstive=0)   //credit webKami at www.webKami.com
    {
        if ($insenstive) {                                          //if its case insenstive, convert strings into lower case
            $haystack=strtolower($haystack);
            $needle=strtolower($needle);
        }
        $count=substr_count($haystack,$needle);                     //count number of occurances  
        if ($count<1 || $nth > $count) return false;                //first check if the needle exists in the haystack, return false if it does not//also check if asked nth is within the count, return false if it doesnt
        for($i=0,$pos=0,$len=0;$i<$nth;$i++)                        //run a loop to nth number of accurance//start $pos from -1, cause we are adding 1 into it while searching//so the very first iteration will be 0
        {   
            $pos=strpos($haystack,$needle,$pos+$len);               //get the position of needle in haystack//provide starting point 0 for first time ($pos=0, $len=0)//provide starting point as position + length of needle for next time
            if ($i==0) $len=strlen($needle);                        //check the length of needle to specify in strpos//do this only first time
        }
        return $pos;                                                //return the number
    }

    $beginSpan = "<span class='tt_subname_sm'>";
    $endSpan = "</span>";
    $t_temp;
    $a_temp;
    $d_temp;
    $c_temp;
    $locationFinish = false;
    $pointsFinish = false;
    $settingsFinish = false;
    if(!$mysqli->query("SELECT * FROM simplemaps_state")){
        $mysqli->query("CREATE TABLE IF NOT EXISTS simplemaps_state (abr VARCHAR(2) NOT NULL,name VARCHAR(120) NOT NULL,description TEXT,zoomable ENUM('yes','no'),inactive ENUM('yes','no'),color VARCHAR(7),title VARCHAR(120), additional TEXT, PRIMARY KEY(abr))");
        foreach($array->state_specific as $key => $value){      
            $i_temp;
            $z_temp;  
            $stmt = $mysqli->prepare("INSERT INTO simplemaps_state (abr,name,description,zoomable,inactive,color,title,additional) VALUES(?,?,?,?,?,?,?,?)");
            if(isset($value->description)){
                $t_temp = get_string_between($value->description,$beginSpan,$endSpan);
                if(strposnth($value->description,$beginSpan,2)){
                    $a_temp = substr($value->description,strposnth($value->description,$beginSpan,2));
                    $d_temp = get_string_between($value->description,$endSpan,$a_temp);
                }else{
                    $a_temp = "";
                    $d_temp = substr($value->description,strpos($value->description,$endSpan)+strlen($endSpan));
                }
            }else{
                $t_temp = "";
                $a_temp = "";
                $d_temp = "";
            }
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
            $stmt->bind_param('ssssssss', $key, $value->name, $d_temp, $z_temp, $i_temp, $value->color, $t_temp, $a_temp);
            $stmt->execute();
        }
        $locationFinish = true;
    }
    if(!$mysqli->query("SELECT * FROM simplemaps_location")){
        $mysqli->query("CREATE TABLE IF NOT EXISTS simplemaps_location (id int NOT NULL AUTO_INCREMENT,lat DOUBLE(10,8), lng DOUBLE(11,8), name VARCHAR(45), description TEXT, title VARCHAR(50), countries TEXT, additional TEXT, PRIMARY KEY(id))");
        foreach($array->locations as $key => $value){
            $stmt = $mysqli->prepare("INSERT INTO simplemaps_location (lat,lng,name,description,title,additional,countries) VALUES(?,?,?,?,?,?,?)");
            if($stmt === false){
                echo $mysqli->error;
            }
            $t_temp = get_string_between($value->description,$beginSpan,$endSpan);
            if(strposnth($value->description,$beginSpan,2)){
                $a_temp = substr($value->description,strposnth($value->description,$beginSpan,2));
                $d_temp = get_string_between($value->description,$endSpan,$a_temp);
            }else{
                $a_temp = "";
                if(strposnth($value->description,"Countries Supported:")){
                    $d_temp = get_string_between($value->description,$endSpan,"Countries Supported:");
                }else{
                    $d_temp = get_string_between($value->description,$endSpan,"Locations Supported:");
                }
            }
            if(strposnth($value->description,"countries supported:",1,1)){
                $c_temp = substr($value->description,strposnth($value->description,"Countries Supported:")+strlen("Countries Supported:"));
            }else{
                $c_temp = substr($value->description,strposnth($value->description,"Locations Supported:")+strlen("Locations Supported:"));
            }
            $stmt->bind_param('ddsssss', $value->lat, $value->lng, $value->name, $d_temp, $t_temp, $a_temp, $c_temp);
            $stmt->execute();
        }
        $pointsFinish = true;
    }
    if(!$mysqli->query("SELECT * FROM simplemaps_settings")){
        $mysqli->query("CREATE TABLE IF NOT EXISTS simplemaps_settings (name VARCHAR(120) NOT NULL, value TEXT, PRIMARY KEY(name))");
        foreach($array->main_settings as $key => $value){
            $stmt = $mysqli->prepare("INSERT INTO simplemaps_settings (name, value) VALUES (?, ?)");
            $stmt->bind_param('ss', $key, $value);
            $stmt->execute();
        }
        $settingsFinish = true;
    }
    if($locationFinish||$pointsFinish||$settingsFinish){
        //header("Refresh:0");
    }

?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#locations">Locations</a></li>
    <li><a data-toggle="tab" href="#points">Points</a></li>
    <li><a data-toggle="tab" href="#settings">Settings</a></li>
</ul>

<div class="tab-content">
    <div id="locations" class="tab-pane fade in active">
        <?php require("location_inc.php"); ?>
    </div>
    <div id="points" class="tab-pane fade">
        <?php require("points_inc.php"); ?>
    </div>
    <div id="settings" class="tab-pane fade">
        <?php require("settings_inc.php"); ?>
    </div>
</div>