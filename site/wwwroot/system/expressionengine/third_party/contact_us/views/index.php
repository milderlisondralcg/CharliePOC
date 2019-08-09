<?php 
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
require("config.php"); ?>
<?php 
    if(is_array($array) || is_object($array)){
        require("mapdata-read.php");
    }else{
        echo "<script type='text/javascript' src='".$mapspathforjs."mapdata.js' ></script>";
        echo "<script>
            console.log('Building file.');
            var simplemaps_data = JSON.stringify(simplemaps_worldmap_mapdata,null,4);
            console.log(simplemaps_data);
            $.ajax({
                url: '".$views."publish.php',
                type: 'POST',
                data: {'data' : simplemaps_data},
                success: function(msg) {
                    console.log(msg);
                    setTimeout(function(){
                        location.reload();
                    }, 100); 
                }               
            });
        </script>";
    }
?>
<script>
$(window).ready(function(){ 
    console.log("Page Loaded");
    $('a[title="Publish"]').click(function(){
        console.log("Publishing");
        $.ajax({
            url: '<?php echo $views ?>publish.php',
            type: 'POST',
            data: {
                publish: "true"
            },
            success: function(msg) {
                setTimeout(function(){
                    location.reload();
                }, 100); 
            }               
        });
    });
});     
</script>
<?php
/* End of file index.php */
/* Location: ./system/expressionengine/third_party/simplemaps_updater/views/index.php */
