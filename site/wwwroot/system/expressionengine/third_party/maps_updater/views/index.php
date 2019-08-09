<?php require("config.php"); error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */


// Uncomment the following for use outside Expression Engine
/*  echo '
<!DOCTYPE html>
<html>
<head>
    <title>Maps Updater</title>
    <!--- Style Sheets --->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/trumbowyg.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/selectize.css">
    <script src="../javascript/jquery.js"></script>
</head>
<body>';
*/
    if(!defined('APP_VER') && $allow_standalone){
        if(is_array($array) || is_object($array)){
            require("mapdata-read.php");
        }else{
            echo "<script type='text/javascript' src='".$mapspathforjs."mapdata.js' charset='utf-8'></script>";
            echo "<script>
                var maps_data = JSON.stringify(simplemaps_worldmap_mapdata,null);
                $.ajax({
                    url: '".$views."publish.php',
                    type: 'POST',
                    data: {'data' : maps_data},
                    success: function(msg) {
                        setTimeout(function(){
                            location.reload();
                        }, 100); 
                    }               
                });
            </script>";
        }
    }else if(defined('APP_VER') && !$allow_standalone){
        if(is_array($array) || is_object($array)){
            require("mapdata-read.php");
        }else{
            echo "<script type='text/javascript' src='".$mapspathforjs."mapdata.js' charset='utf-8'></script>";
            echo "<script>
                var maps_data = JSON.stringify(simplemaps_worldmap_mapdata,null);
                $.ajax({
                    url: '".$views."publish.php',
                    type: 'POST',
                    data: {'data' : maps_data},
                    success: function(msg) {
                        setTimeout(function(){
                            location.reload();
                        }, 100); 
                    }               
                });
            </script>";
        }
    }
?>
<script>
$(window).ready(function(){ 
    $('a[title="Publish"]').click(function(){
        $.ajax({
            url: '<?php echo $views ?>publish.php',
            type: 'POST',
            data: {
                publish: "true"
            },
            success: function(msg) {
            }               
        });
    });
});     
</script>
<?php
// Uncomment the following for use outside Expression Engine
/* echo '
    <script src="../javascript/scripts.js"></script>
    <script src="../javascript/boostrap.js"></script>
    <script src="../javascript/trumbowyg.js"></script>
    <script src="../javascript/selectize.js"></script>
</body>
</html>';
*/


// DO NOT MODIFY BELOW THIS LINE
//---------------------------------------------------------------------

/* End of file index.php */
?>