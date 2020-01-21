<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$media = new Media();
$app_codes = $media->get_app_codes(); 
$azuremm = new AzureMM();
$containers = $azuremm->get_containers();
asort($containers);

$categories = $media->get_categories();
$tags_array = $media->get_tags();

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Add Media</h2>
        </div>

        <div class="page-contents">


	<form id="add-media" name="add-media" action="../_admin_mm/controllers/index.php" method="post" enctype="multipart/form-data">
	
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Title">Media Title</label>
			</div>

			<div class="form-col-input">
				<input id="Title" name="Title" type="text" value="" placeholder="Enter Title" required />
			</div>
		</div>
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Description">Description</label>
			</div>
			<div class="form-col-input">
				<textarea class="form-control" cols="80" id="Description" name="Description" rows="5" placeholder="Enter Description"></textarea>                 
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Folder">Folder</label>
			</div>
 			<div class="form-col-input">
			<?php if( $_SESSION['group_id'] != 13 && $_SESSION['group_id'] != 14){ ?>
				<select id="Folder" name="Folder" size="<?php print count($containers) + 1; ?>" required>
				<option value="">-- Select One Folder --</option>
				<?php
					foreach( $containers as $key=>$value){
						?>
						<option value="<?php print $key;?>"><?php print $value;?></option>
						<?php
					}
				?>
				
			<?php }else{ ?>
				<select id="Folder" name="Folder" size="1" required>
				<option value="optoskand" selected>Optoskand</option>
			<?php } ?>
				</select>
			</div>
		</div>  

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Category">Category(s)  &nbsp; <em>- separate categories by commas</em> </label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Category" name="Category" type="text" value="" />		
			</div>
		</div>	
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Tags">Tag(s)  &nbsp; <em>- separate tags by commas</em> </label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Tags" name="Tags" type="text" value="" />
			</div>
		</div>		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Group">Group(s)  &nbsp; <em>- separate groups by commas</em> </label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Group" name="Group" type="text" value="" />
			</div>
		</div>		

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="uploadImage">Media</label>
			</div>			
			<div class="form-col-input">
				<input id="file_upload" type="file" name="file_upload" required/>
				<div id="preview"></div><br>
				<div id="upload_notification"></div>
			</div>		
		</div>
		
		<input type="submit" value="Add">
		<input type="button" id="clear_add_form" value="Reset">
		<input type="hidden" name="Type" value="Document">
		<input type="hidden" name="action" value="add">
	</form>
	
	</div>
</div>
	<?php
		$category_list = "";
		$tags_list = "";
		
		foreach($categories as $category){
			$category_list .= '"' . $category . '",';
		}
		$category_list = rtrim($category_list,",");
		
		foreach($tags_array as $tag){
			$tags_list .= '"' . $tag . '",';
		}
		$tags_list = rtrim($tags_list,",");		
		
	?>
	<script>
		var categories = [<?php print trim($category_list);?>];	
		categories.sort();
		console.log(categories);
		var tags = [<?php print trim($tags_list);?>];
		tags.sort();
		console.log(tags);
		
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
		  return split( term ).pop();
		}
	
		$( "#Category" )
		  // don't navigate away from the field on tab when selecting an item
		  .on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
			  event.preventDefault();
			}
		  })
		  .autocomplete({
			minLength: 0,
			source: function( request, response ) {
			  // delegate back to autocomplete, but extract the last term
			  response( $.ui.autocomplete.filter(
				categories, extractLast( request.term ) ) );
			},
			focus: function() {
			  // prevent value inserted on focus
			  return false;
			},
			select: function( event, ui ) {
			  var terms = split( this.value );
			  // remove the current input
			  terms.pop();
			  // add the selected item
			  terms.push( ui.item.value );
			  // add placeholder to get the comma-and-space at the end
			  terms.push( "" );
			  this.value = terms.join( ", " );
			  return false;
			}
		  });
		
		$( "#Tags" )
		  // don't navigate away from the field on tab when selecting an item
		  .on( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
			  event.preventDefault();
			}
		  })
		  .autocomplete({
			minLength: 0,
			source: function( request, response ) {
			  // delegate back to autocomplete, but extract the last term
			  response( $.ui.autocomplete.filter(
				tags, extractLast( request.term ) ) );
			},
			focus: function() {
			  // prevent value inserted on focus
			  return false;
			},
			select: function( event, ui ) {
			  var terms = split( this.value );
			  // remove the current input
			  terms.pop();
			  // add the selected item
			  terms.push( ui.item.value );
			  // add placeholder to get the comma-and-space at the end
			  terms.push( "" );
			  this.value = terms.join( ", " );
			  return false;
			}
		  });		  
	</script>

<?php
include('includes/footer.php');
?>