<?php 
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
require("config.php");
    if(isset($_POST["data"])){
        $mapdata = $_POST["data"];
        $myfile = fopen($mapspath."mapdata.js", "w");
        fwrite($myfile,"var simplemaps_worldmap_mapdata=".$mapdata.";");
        fclose($myfile);
    }
    if(isset($_POST["publish"])){
        $state = $mysqli->query("SELECT * FROM simplemaps_state");
        $settings = $mysqli->query("SELECT * FROM simplemaps_settings");
        $location = $mysqli->query("SELECT lat, lng, name, description, title, additional, countries FROM simplemaps_location");
        $main_settings = array();
        $state_specific = array();
        $locations = array();
        while($row = $settings->fetch_assoc()){
            $main_settings[$row['name']] = $row['value'];
        };
        while($row = $state->fetch_assoc()){
            $group = array();
            $group['name'] = $row['name'];
            if($row['inactive'] != "yes"){
                $desc = str_replace('"', '&apos;',$row['description']);
                $desc = str_replace('<p>', '', $desc);
                $desc = str_replace('</p>', '<br/>', $desc);
                $add = str_replace('"', '&apos;',$row['additional']);
                $add = str_replace('<p>', '', $add);
                $add = str_replace('</p>', '<br/>', $add);
                $group['description'] = "<span class='tt_subname_sm'>".$row['title']."</span>".$desc.$add;
                $group['zoomable'] = $row['zoomable'];
                $group['color'] = $row['color'];
            }else{
                $group['inactive'] = $row['inactive'];
            }
            $state_specific[$row['abr']] = $group;   
        };
        while($row = $location->fetch_assoc()){
            $group = array();
            $group['lat'] = $row['lat'];
            $group['lng'] = $row['lng'];
            $group['name'] = $row['name'];
            $desc = str_replace('"', '&apos;',$row['description']);
            $desc = str_replace('<p>', '', $desc);
            $desc = str_replace('</p>', '<br/>', $desc);
            if($row['additional'] != ""){
                $add = str_replace('"', '&apos;',$row['additional']);
                $add = str_replace('<p>', '', $add);
                $add = str_replace('</p>', '<br/>', $add);
                $group['description'] = "<span class='tt_subname_sm'>".$row['title']."</span><br/>".$desc.$add."<br/>Locations Supported: ".$row['countries'];
            }else{
                $group['description'] = "<span class='tt_subname_sm'>".$row['title']."</span><br/>".$desc."<br/>Locations Supported: ".$row['countries'];
            }
            
            $locations[] = $group;
        };
        $publishMap['main_settings'] = $main_settings;
        $publishMap['state_specific'] = $state_specific;
        $publishMap['locations'] = $locations;
        $publishMap['regions'] = array();
        $myfile = fopen($mapspath."mapdata.js", "w");
        fwrite($myfile,"var simplemaps_worldmap_mapdata=".json_encode($publishMap, JSON_PRETTY_PRINT).";");
        fclose($myfile);
    }
?>