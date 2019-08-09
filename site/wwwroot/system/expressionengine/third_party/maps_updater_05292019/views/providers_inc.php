<?php error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-04-25
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
?>
<?php
    $providers = $mysqli->query("SELECT * FROM maps_providers");
?>
<form>
    <div id="providersWarning"  class="alert alert-danger" style="display:none; width:100%;">After changing the attributes you must refresh the page to modify any providers.</div>
    <table class="table table-bordered" id="mapProviders">
        <tbody>
            <tr>
                <th class="col-xs-1" align="center">Delete</th>
                <th class="col-xs-1" align="center">Update</th>
                <th class="col-xs-10">Content</th>
            </tr>
                <?php	
                if($providers){
                    while($row = $providers->fetch_assoc()){
                        echo '<tr id="'.$row["id"].'">';
                        echo '<td align="center" class="delete">
                            <button type="button" class="btn btn-default btn-sm" onclick="deletePopup(this, \'provider\')" class="deleteEntry" ><span class="glyphicon glyphicon-trash"></span> Trash</button>
                            <input type="hidden" name="provider_delete[]" value="false" /><input type="hidden" class="newEntry" name="provider_new[]" value="false"/>
                            </td>';
                        echo '<td align="center" class="update">
                            <button type="button" name="provider_update[]" class="btn btn-default btn-sm" onClick="editClick(this)" class="updateEntry" ><span class="glyphicon glyphicon-wrench"></span> Edit</button></td>';
                        echo '<td>
                            <input type="hidden" name="hidden_provider_title[]" value="'.$row["title"].'"/>
                            <input type="hidden" name="hidden_provider_address[]" value="'.$row["address"].'"/>
                            <input type="hidden" name="hidden_provider_phone[]" value="'.$row["phone"].'"/>
                            <input type="hidden" name="hidden_provider_email[]" value="'.$row["email"].'"/>
                            <input type="hidden" name="hidden_provider_website[]" value="'.$row["website"].'"/>
                            <input type="hidden" name="hidden_provider_countries[]" value="'.$row["countries"].'"/>
                            <input type="hidden" name="hidden_provider_additional[]" value="'.$row["additional"].'"/>
                            <input type="hidden" name="hidden_provider_categories[]" value="'.$row["categories"].'"/>
                            <input type="hidden" name="hidden_provider_products[]" value="'.$row["products"].'"/>
                            <input type="hidden" name="hidden_provider_trainings[]" value="'.$row["trainings"].'"/>
                            <input type="hidden" name="hidden_provider_sales[]" onchange="checkChange(this)" value="'.$row["sales"].'"/>';
						?>
								<input type="hidden" name="hidden_provider_sales_phone[]" value="<?php print $row['sales_phone']; ?>"/>
								<input type="hidden" name="hidden_provider_sales_email[]" value="<?php print $row['sales_email']; ?>"/>
						<?php
                         echo '<input type="hidden" name="hidden_provider_service[]" onchange="checkChange(this)" value="'.$row["service"].'"/>';
						 ?>
								<input type="hidden" name="hidden_provider_service_phone[]" value="<?php print $row['service_phone']; ?>"/>
								<input type="hidden" name="hidden_provider_service_email[]" value="<?php print $row['service_email']; ?>"/>
								<input type="hidden" name="hidden_provider_training_phone[]" value="<?php print $row['training_phone']; ?>"/>
								<input type="hidden" name="hidden_provider_training_email[]" value="<?php print $row['training_email']; ?>"/>								
						<?php
						   echo '<input type="hidden" name="hidden_provider_priority[]" value="'.$row["priority"].'"/>
							<input type="hidden" name="hidden_provider_visible[]" onchange="checkChange(this)" value="'.$row["visible"].'"/>
                        '.$row["title"].' : '.$row["address"].'</td>';
                        echo '</tr>';
                    };
                    echo '<tr><td></td><td align="center"><button type="button" onClick="addProvider()" class="btn btn-default btn-sm" class="addEntry" ><span class="glyphicon glyphicon-plus"></span> Add</button></td><td></td></tr>';
                };
                ?>
        </tbody>
    </table>
</form>
<script>
    var providersPath = '<?php echo $views ?>updateProviders.php';
</script>