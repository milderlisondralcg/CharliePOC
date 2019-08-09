<?php error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-03-12
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
    $state = $mysqli->query("SELECT * FROM maps_state");
?>
<form method="POST">
<table  class="table table-bordered" id="mapStates">
    <tbody>
        <tr>
            <th class="col5">Update</th>
            <th class="col5">Abbr</th>
            <th class="col6">Name</th>
            <th class="col5">Inactive</th>
            <th class="col6">Content</th>
            <th class="col5">Zoomable</th>
            <th class="col5">Color</th>
        </tr>
            <?php	
            if($state){
                while ($row = $state->fetch_assoc()) {
					extract($row);
                    echo '<tr>';

                    //print update checkbox
                    echo '<td align="center" class="state_update"><button type="button" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button></td>';
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

                    //print content
                    echo '<td class="state_content"><input type="hidden" name="state_content_hidden[]" value="'.$row['content'].'" />'.str_replace(",!%%!,", ", ", $row['content']).'</td>';

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
</form>
<script>
    var locationPath = '<?php echo $views ?>updateLocation.php';
</script>