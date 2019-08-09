<?php
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
    $settings = $mysqli->query("SELECT * FROM simplemaps_settings");
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
                    echo '<td align="center" class="setting_update"><input type="checkbox" class="updateEntry" name="setting_update[]" value="false" /></td>';
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
<div class="formButtons">
    <button type="button" class="btn btn-success" id="updateSettings">Save</button>
</div>
</form>
<script>
    $("#updateSettings").click(function(){
        var i = 0;
        var sendData = {};
        var update = {};
        var key = {};
        var value = {};
        $('#mapSettings tr').each(function(){
            if(i != 0){
                if($(this).children('td.setting_update').find('input.updateEntry').val() == "true"){
                    var name = $(this).children('td.setting_name').find('input:hidden').val();
                    var setting = $(this).children('td.setting_value').find('input:text').val();
                    var isUpdate = $(this).find('input:checkbox[name="setting_update[]"]').val();
                    update[i - 1] = isUpdate;
                    key[i - 1] = name;
                    value[i - 1] = setting;
                }else{
                    var name = $(this).children('td.setting_name').find('input:hidden').val();
                    var setting = $(this).children('td.setting_value').find('input:hidden').val();
                    var isUpdate = $(this).find('input:checkbox[name="setting_update[]"]').val();
                    update[i - 1] = isUpdate;
                    key[i - 1] = name;
                    value[i - 1] = setting;
                }
            }
            i++;
        });
        sendData["setting_update"] = update;
        sendData["setting_name"] = key;
        sendData["setting_value"] = value;
        $.ajax({
            url: '<?php echo $views?>updateSettings.php',
            type: 'POST',
            data: sendData,
            success: function(msg) {
                setTimeout(function(){
                    location.reload();
                }, 100); 
            }               
        });
    });
</script>