<?php error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
    
?>
<script>
    var providers = [
        <?php
            $attr0 = $mysqli->query("SELECT * FROM maps_providers");
            $z = 1;
            $numProv = $attr0->num_rows;
            if($attr0){
                while($row = $attr0->fetch_assoc()){
                    if($row['visible'] == "1"){
                        if($z < $numProv){
                            echo '"'.$row["title"].'",';
                        }else{
                            echo '"'.$row["title"].'"';
                        }
                    }
                    $z++;
                }
            }
        ?>
    ];
    var categories = [
        <?php
            $attr1 = $mysqli->query("SELECT * FROM maps_categories");
            $x = 1;
            $numCat = $attr1->num_rows;
            if($attr1){
                while($row = $attr1->fetch_assoc()){
                    if($x < $numCat){
                            echo '"'.$row["category"].'",';
                    }else{
                            echo '"'.$row["category"].'"';
                    }
                    $x++;
                }
            }
        ?>
    ];
    var countries = [ "China", "Hong Kong", "Beijing",
        <?php
            $attr2 = $mysqli->query("SELECT name FROM maps_state");
            $k = 1;
            $numCountries = $attr2->num_rows;
            if($attr2){
                while($row = $attr2->fetch_assoc()){
                    if($k < $numCountries){
                            echo '"'.$row["name"].'",';
                    }else{
                            echo '"'.$row["name"].'"';
                    }
                    $k++;
                }
            }
        ?>
    ];
    countries.sort();
    var products = [
        <?php
            $attr3 = $mysqli->query("SELECT product FROM maps_custom_products UNION SELECT product FROM maps_products ORDER BY product");
            $p = 1;
            $numPro = $attr3->num_rows;
            if($attr3){
                while($row = $attr3->fetch_assoc()){
                    if($p < $numPro){
                            echo '"'.$row["product"].'",';
                    }else{
                            echo '"'.$row["product"].'"';
                    }
                    $p++;
                }
            }
        ?>
    ];
</script>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#locations">Locations</a></li>
    <li><a data-toggle="tab" href="#points">Points</a></li>
    <li><a data-toggle="tab" href="#settings">Settings</a></li>
    <li><a data-toggle="tab" href="#providers">Providers</a></li>
    <li><a data-toggle="tab" href="#attributes">Attributes</a></li>
    <?php 
    if(!defined('APP_VER')){
        echo "<li style='float:right;cursor:pointer;'><a title='Publish'>Publish</a></li>";
    } ?>
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
    <div id="providers" class="tab-pane fade">
        <?php require("providers_inc.php"); ?>
    </div>
    <div id="attributes" class="tab-pane fade">
        <?php require("attributes.php"); ?>
    </div>
</div>