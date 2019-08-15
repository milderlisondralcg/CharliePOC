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
<form method="POST">

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $GLOBALS['GAPI'] ?>&libraries=places"></script>
<?php
    $location = $mysqli->query("SELECT lat, lng, name, content, border_color, location_color, border, hover_color, `group`, id FROM maps_location");
?>
<table class="table table-bordered" id="mapLocations">
    <tbody>
        <tr>
            <th class="col-xs-1" align="center">Delete</th>
            <th class="col-xs-1" align="center">Update</th>
            <th class="col-xs-2">Name</th>
            <th class="col-xs-2">Latitude, Longitude</th>
            <th class="col-xs-6">Providers</th>
			<th class="col-xs-6">Border Color</th>
			<th class="col-xs-6">Color</th>
			<th class="col-xs-6">Border Size</th>
			<th class="col-xs-6">Hover Color</th>
			<th class="col-xs-6">Group</th>
        </tr>
            <?php	
            if($location){
                while($row = $location->fetch_assoc()){
                    echo '<tr id="point-'.$row["id"].'">';

                    //print trash button
                    echo '<td align="center" class="location_delete"><button type="button" class="btn btn-default btn-sm" onclick="deletePopup(this, \'point\')" class="deleteEntry" ><span class="glyphicon glyphicon-trash"></span> Trash</button><input type="hidden" name="location_delete[]" value="false" /><input type="hidden" class="newEntry" name="location_new[]" value="false"/></td>';

                    //print edit button
                    echo '<td align="center" class="location_update"><button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button></td>';

                    //print location name
                    echo '<td class="location_name"><input type="hidden" name="location_name_hidden[]" value="'.$row["name"].'"/>'.$row["name"].'</td>';

                    //print location latitude
                    echo '<td align="center" class="latlng"><input type="hidden" name="location_lng_hidden[]" value="'.$row["lng"].'"/><input type="hidden" name="location_lat_hidden[]" value="'.$row["lat"].'"/>'.$row["lat"].',<br>'.$row["lng"].'</td>';

                    //print content
                    echo '<td class="location_content"><input type="hidden" name="location_content_hidden[]" value="'.$row['content'].'" />'.str_replace(",!%%!,", ", ", $row['content']).'</td>';
					
					// border color
                    if(!empty($row['border_color'])){
                        echo '<td align="center" class="border_color" style="background-color:'.$row['border_color'].'"><input type="hidden" name="border_color_hidden" value="'.$row['border_color'].'" ></td>';
                    }else{
                        echo '<td align="center" class="border_color"><input type="hidden" name="border_color_hidden" value="" >No Color</td>';
                    };					
					
					// location_color
                    if(!empty($row['location_color'])){
                        echo '<td class="location_color" style="background-color:'.$row['location_color'].'"><input type="hidden" name="location_color_hidden" value="'.$row['location_color'].'" ></td>';
                    }else{
                        echo '<td class="location_color"><input type="hidden" name="location_color_hidden" value="" >No Color</td>';
                    };
					
					// Border size
					echo '<td class="border"><input type="hidden" name="border_hidden" value="'.$row['border'].'" />'.str_replace(",!%%!,", ", ", $row['border']).'</td>';
					
					// hover color			
                    if(!empty($row['hover_color'])){
                        echo '<td align="center" class="hover_color" style="background-color:'.$row['hover_color'].'"><input type="hidden" name="hover_color_hidden" value="'.$row['hover_color'].'" ></td>';
                    }else{
                        echo '<td align="center" class="hover_color"><input type="hidden" name="hover_color_hidden" value="" >No Color</td>';
                    };					

					?>
					<td class="location_group">
						<?php $group_name = "group_".$row['id']; ?>
						<input type="radio" value="0" name="<?php print $group_name; ?>" <?php if( $row['group'] == 0){ print 'checked'; } ?> class="group_select"> Representatives <br/>
						<input type="radio" value="1" name="<?php print $group_name; ?>" <?php if( $row['group'] == 1){ print 'checked'; } ?> class="group_select"> Training Offices <br/>
						<input type="radio" value="2" name="<?php print $group_name; ?>" <?php if( $row['group'] == 2){ print 'checked'; } ?> class="group_select"> Sales/Service Offices<br/>
						<input type="radio" value="3" name="<?php print $group_name; ?>" <?php if( $row['group'] == 3){ print 'checked'; } ?> class="group_select"> Service/Training Offices <br/>
						<input type="radio" value="4" name="<?php print $group_name; ?>" <?php if( $row['group'] == 4){ print 'checked'; } ?> class="group_select"> Sales/Service/Training Offices <br/>
					</td>
					<?php
                    echo '</tr>';
                };
                echo '<tr><td></td><td align="center"><button type="button" onClick="addLocation()" class="btn btn-default btn-sm" class="addEntry" ><span class="glyphicon glyphicon-plus"></span> Add</button></td><td></td><td></td><td></td></tr>';
            };
            ?>
    </tbody>
</table>
</form>
<script>
    var pointsPath = '<?php echo $views ?>updatePoints.php';
</script>