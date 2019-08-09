<?php
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Simplemaps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-03-30
 * 
 */
?>
<form method="POST">

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GLOBALS['GAPI'] ?>&libraries=places"></script>
<script>
    var countries = [
        <?php
            $location = $mysqli->query("SELECT lat, lng, name, description, title, additional, countries FROM simplemaps_location");
            $state = $mysqli->query("SELECT * FROM simplemaps_state");
            $x = 1;
            $numCountries = $state->num_rows;
            if($state){
                while($row = $state->fetch_assoc()){
                    echo '"China",';
                    if($x < $numCountries){
                        echo '"'.$row["name"].'",';
                    }else{
                        echo '"'.$row["name"].'"';
                    }
                    $x++;
                }
            }
        ?>
    ];
</script>
<table class="table table-bordered" id="mapLocations">
    <tbody>
        <tr>
            <th class="col1" align="center">Delete</th>
            <th class="col1" align="center">Update</th>
            <th class="col2">Name</th>
            <th class="col2">Title</th>
            <th class="col2">Latitude, Longitude</th>
            <th class="col3">Description</th>
            <th class="col3">Additional</th>
            <th class="col2">Supported</th>
        </tr>
            <?php	
            if($location){
                while($row = $location->fetch_assoc()){
                    echo '<tr>';

                    //print delete checkbox
                    echo '<td align="center" class="location_delete"><input type="checkbox" class="deleteEntry" name="location_delete[]" value="false" /><input type="hidden" class="newEntry" name="location_new[]" value="false"/></td>';

                    //print update checkbox
                    echo '<td align="center" class="location_update"><input type="checkbox" class="updateEntry" name="location_update[]" value="false" /></td>';

                    //print location name
                    echo '<td class="location_name"><input type="hidden" name="location_name_hidden[]" value="'.$row["name"].'"/>'.$row["name"].'</td>';

                    echo '<td align="center" class="title"><input type="hidden" name="location_title_hidden[]" value="'.$row["title"].'"/>'.$row["title"].'</td>';
                    
                    //print location latitude
                    echo '<td align="center" class="latlng"><input type="hidden" name="location_lng_hidden[]" value="'.$row["lng"].'"/><input type="hidden" name="location_lat_hidden[]" value="'.$row["lat"].'"/>'.$row["lat"].',<br>'.$row["lng"].'</td>';

                    //print location latitude
                    //echo '<td align="center" class="lng"><input type="hidden" name="location_lng_hidden[]" value="'.$row["lng"].'"/>'.$row["lng"].'</td>';
                    
                    //check description status and print
                    if(!empty($row['description'])){
                        echo '<td class="description"><input type="hidden" name="location_description_hidden[]" value="'.str_replace('"','&apos;',$row['description']).'" />'.$row['description'].'</td>';
                    }else{
                        echo '<td class="description"><input type="hidden" name="location_description_hidden[]" value="" />No Description</td>';
                    };

                    //check additional status and print
                    if(!empty($row['additional'])){
                        echo '<td class="additional"><input type="hidden" name="location_additional_hidden[]" value="'.str_replace('"','&apos;',$row['additional']).'" />'.$row['additional'].'</td>';
                    }else{
                        echo '<td class="additional"><input type="hidden" name="location_additional_hidden[]" value="" />No Additional Information</td>';
                    };
                    echo '<td align="center" class="countries"><input type="hidden" name="location_countries_hidden[]" value="'.$row["countries"].'"/>'.$row["countries"].'</td>';

                    echo '</tr>';
                };
            };
            ?>
    </tbody>
</table>
<div class="formButtons">
    <button type="button" class="btn btn-success" id="updateLocation">Save</button>
    <button type="button" id="addLocation" class="btn btn-primary">Add</button>
</div>
</form>
<script>
    $("#updateLocation").click(function(){
        var i = 0;
        var sendData = {};
        var deleted = {};
        var update = {};
        var names = {};
        var lats = {};
        var lngs = {};
        var titles = {};
        var news = {};
        var descs = {};
        var addtls = {};
        var countries ={};
        $('#mapLocations tr').each(function(){
            if(i != 0){
                if($(this).children('td.location_update').find('input.updateEntry').val() == "true"){
                    var name = $(this).children('td.location_name').find('input:text').val();
                    var lat = $(this).children('td.latlng').find('input:text[name="location_lat[]"]').val();
                    var lng = $(this).children('td.latlng').find('input:text[name="location_lng[]"]').val();
                    var title = $(this).children('td.title').find('input:text').val();
                    var isUpdate = $(this).find('input:checkbox[name="location_update[]"]').val();
                    var isDelete = $(this).find('input:checkbox[name="location_delete[]"]').val();
                    var isNew = $(this).children('td.location_delete').find('input:hidden').val();
                    var desc = $(this).children('td.description').find('.trumbowyg-editor').html();
                    var additional = $(this).children('td.additional').find('.trumbowyg-editor').html();
                    var country = $(this).children('td.countries').find('select[name="location_countries[]"]').val();
                    addtls[i-1] = additional;
                    descs[i-1] = desc;
                    if(country.constructor === Array){
                        countries[i-1] = country.toString();
                    }else{
                        countries[i-1] = country;
                    }
                    news[i-1] = isNew;
                    deleted[i-1] = isDelete;
                    update[i - 1] = isUpdate;
                    names[i - 1] = name;
                    lats[i - 1] = lat;
                    lngs[i-1] = lng;
                    titles[i-1] = title;
                    
                }else if($(this).children('td.location_delete').find('input:hidden').val() == "true"){
                    var name = $(this).children('td.location_name').find('input').val();
                    var lat = $(this).children('td.latlng').find('input:text[name="location_lat[]"]').val();
                    var lng = $(this).children('td.latlng').find('input:text[name="location_lng[]"]').val();
                    var title = $(this).children('td.title').find('input').val();
                    var isUpdate = $(this).find('input:checkbox[name="location_update[]"]').val();
                    var isDelete = $(this).find('input:checkbox[name="location_delete[]"]').val();
                    var isNew = $(this).children('td.location_delete').find('input:hidden').val();
                    var desc = $(this).children('td.description').find('.trumbowyg-editor').html();
                    var additional = $(this).children('td.additional').find('.trumbowyg-editor').html();
                    var country = $(this).children('td.countries').find('select[name="location_countries[]"]').val();
                    descs[i-1] = desc;
                    addtls[i-1] = additional;
                    if(country.constructor === Array){
                        countries[i-1] = country.toString();
                    }else{
                        countries[i-1] = country;
                    }
                    news[i-1] = isNew;
                    deleted[i-1] = isDelete;
                    update[i - 1] = isUpdate;
                    names[i - 1] = name;
                    lats[i - 1] = lat;
                    lngs[i-1] = lng;
                    titles[i-1] = title;
                }
                else{
                    var name = $(this).children('td.location_name').find('input:hidden').val();
                    var lat = $(this).children('td.latlng').find('input:hidden[name="location_lat_hidden[]"]').val();
                    var lng = $(this).children('td.latlng').find('input:hidden[name="location_lng_hidden[]"]').val();
                    var title = $(this).children('td.title').find('input:hidden').val();
                    var isUpdate = $(this).find('input:checkbox[name="location_update[]"]').val();
                    var isDelete = $(this).find('input:checkbox[name="location_delete[]"]').val();
                    var isNew = $(this).children('td.location_delete').find('input:hidden').val();
                    var desc = $(this).children('td.description').find('input:hidden[name="location_description_hidden[]"]').val();
                    var additional = $(this).children('td.additional').find('input:hidden[name="location_additional_hidden[]"]').val();
                    var country = $(this).children('td.countries').find('input:hidden').val();
                    descs[i-1] = desc;
                    addtls[i-1] = additional;
                    if(country.constructor === Array){
                        countries[i-1] = country.toString();
                    }else{
                        countries[i-1] = country;
                    }
                    news[i-1] = isNew;
                    deleted[i-1] = isDelete;
                    update[i - 1] = isUpdate;
                    names[i - 1] = name;
                    lats[i - 1] = lat;
                    lngs[i-1] = lng;
                    titles[i-1] = title;
                };
            };
            i++;
        });
        sendData["location_new"] = news;
        sendData["location_delete"] = deleted;
        sendData["location_update"] = update;
        sendData["location_name"] = names;
        sendData["location_lat"] = lats;
        sendData["location_lng"] = lngs;
        sendData["location_title"] = titles;
        sendData["location_description"] = descs;
        sendData["location_additional"] = addtls;
        sendData["location_countries"] = countries;
        $.ajax({
            url: '<?php echo $views ?>updatePoints.php',
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