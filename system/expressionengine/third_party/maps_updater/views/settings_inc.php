<?php error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
    $settings = $mysqli->query("SELECT * FROM maps_settings");
?>
<form method="POST">
<table class="table table-bordered" id="mapSettings">
    <tbody>
        <tr>
            <th class="col-xs-1">Update</th>
            <th class="col-xs-2">Setting</th>
            <th class="col-xs-9">Value</th>
        </tr>
            <?php	
            if($settings){
                while ($row = $settings->fetch_assoc()) {
                    echo '<tr>';

                    //print update checkbox
                    echo '<td align="center" class="setting_update"><button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button></td>';
                    //print setting name
                    echo '<td align="center" class="setting_name"><input type="hidden" name="setting_name_hidden[]" value="'.$row['name'].'"/>'.$row['name'].'</td>';
                    //print setting value
                    echo '<td align="left" class="setting_value"><input type="hidden" name="setting_value_hidden[]" value="'.$row['value'].'"/>'.$row['value'].'</td>';

                    echo '</tr>';
                };
            };
            ?>
    </tbody>
</table>
</form>
<script>
    var settingsPath = '<?php echo $views?>updateSettings.php';
</script>