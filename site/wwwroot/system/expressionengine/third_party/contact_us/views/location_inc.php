<?php
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
    $state = $mysqli->query("SELECT * FROM simplemaps_state");
?>

<form method="POST">
<table  class="table table-bordered" id="mapStates">
    <tbody>
        <tr>
            <th class="col5">Update</th>
            <th class="col5">Abbr</th>
            <th class="col6">Name</th>
            <th class="col5">Inactive</th>
            <th class="col6">Title</th>
            <th class="col7">Description</th>
            <th class="col7">Additional</th>
            <th class="col5">Zoomable</th>
            <th class="col5">Color</th>
        </tr>
            <?php	
            if($state){
                while ($row = $state->fetch_assoc()) {
                    echo '<tr>';

                    //print update checkbox
                    echo '<td align="center" class="state_update"><input type="checkbox" class="updateEntry" name="state_update[]" value="false" /></td>';
                    //print state abbreviation
                    echo '<td align="center" class="state_abbr" ><input type="hidden" name="state_abbr_hidden[]" value="'.$row['abr'].'"/>'.$row['abr'].'</td>';
                    //print state name
                    echo '<td align="center" class="state_name" ><input type="hidden" name="state_name_hidden[]" value="'.$row['name'].'"/>'.$row['name'].'</td>';

                    //check inactive status and print
                    if(($row['inactive']=="yes")){
                        echo '<td align="center" class="state_inactive"><input type="checkbox" name="state_inactive[]" value="true" checked disabled="disabled"/></td>';
                    }else{
                        echo '<td align="center" class="state_inactive"><input type="checkbox" name="state_inactive[]" value="false" disabled="disabled"/></td>';
                    };

                    //print title
                    echo '<td class="state_title"><input type="hidden" name="state_title_hidden[]" value="'.$row['title'].'" />'.$row['title'].'</td>';

                    //check description status and print
                    if(!empty($row['description'])){
                        echo '<td class="state_description"><input type="hidden" name="state_desc_hidden[]" value="'.str_replace('"','&apos;',$row['description']).'" />'.$row['description'].'</td>';
                    }else{
                        echo '<td class="state_description"><input type="hidden" name="state_desc_hidden[]" value="" />No Description</td>';
                    };

                    //check additional status and print
                    if(!empty($row['additional'])){
                        echo '<td class="state_additional"><input type="hidden" name="state_additional_hidden[]" value="'.str_replace('"','&apos;',$row['additional']).'" />'.$row['additional'].'</td>';
                    }else{
                        echo '<td class="state_additional"><input type="hidden" name="state_additional_hidden[]" value="" />No Additional Information</td>';
                    };

                    //check zoomable status and print
                    if(($row['zoomable']=="yes")){
                        echo '<td align="center" class="state_zoomable"><input type="checkbox" name="state_zoomable[]" value="true" checked disabled="disabled"/></td>';
                    }else{
                        echo '<td align="center" class="state_zoomable"><input type="checkbox" name="state_zoomable[]" value="false" disabled="disabled"/></td>';
                    };

                    //check color status and print
                    if(!empty($row['color'])){
                        echo '<td align="center" class="state_color" style="background-color:'.$row['color'].'"><input type="hidden" name="state_color_hidden[]" value="'.$row['color'].'" ></td>';
                    }else{
                        echo '<td align="center" class="state_color"><input type="hidden" name="state_color_hidden[]" value="" >No Color</td>';
                    };

                    echo '</tr>';
                };
            };
            ?>
    </tbody>
</table>
<div class="formButtons">
    <button type="button" class="btn btn-success" id="updateStates">Save</button>
</div>
</form>
<script>
    $("#updateStates").click(function(){
        var i = 0;
        $('#mapStates tr').each(function(){
            var sendData = {};
            var update = {};
            var abbrevs = {};
            var names = {};
            var inactives = {};
            var titles = {};
            var descs = {};
            var addtls = {};
            var zooms = {};
            var colors = {};
            if(i != 0){
                if($(this).children('td.state_update').find('input.updateEntry').val() == "true"){
                    var abbrev = $(this).children('td.state_abbr').find('input:text').val();
                    var name = $(this).children('td.state_name').find('input:text').val();
                    var inactive = $(this).children('td.state_inactive').find('input:checkbox[name="state_inactive[]"]').val();
                    var title = $(this).children('td.state_title').find('input:text').val();
                    var description = $(this).children('td.state_description').find('.trumbowyg-editor').html();
                    var addtl = $(this).children('td.state_additional').find('.trumbowyg-editor').html();
                    var zoom = $(this).children('td.state_zoomable').find('input:checkbox[name="state_zoomable[]"]').val();
                    var color = $(this).children('td.state_color').find('input[name="state_color[]"]').val();
                    var isUpdate = $(this).find('input:checkbox[name="state_update[]"]').val();
                    update[i - 1] = isUpdate;
                    abbrevs[i-1] = abbrev;
                    names[i - 1] = name;
                    inactives[i - 1] = inactive;
                    titles[i-1] = title;
                    descs[i-1] = description;
                    addtls[i-1] = addtl;
                    zooms[i-1] = zoom;
                    colors[i-1] = color;
                    sendData["state_update"] = update;
                    sendData["state_abbr"] = abbrevs;
                    sendData["state_name"] = names;
                    sendData["state_inactive"] = inactives;
                    sendData["state_title"] = titles;
                    sendData["state_description"] = descs;
                    sendData["state_additional"] = addtls;
                    sendData["state_zoomable"] = zooms;
                    sendData["state_color"] = colors;
                    
                    $.ajax({
                        url: '<?php echo $views ?>updateLocation.php',
                        type: 'POST',
                        data: sendData,
                        success: function(msg) {
                            setTimeout(function(){
                                location.reload();
                            }, 100); 
                        }               
                    });
                }
            };
            i++;
        });
        
    });
</script>