<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$media = new Media();
$app_codes = $media->get_app_codes(); 
$MediaID = trim($_GET['mediaid']);
$media_info = $media->get($MediaID);
extract($media_info);

$categories = $media->get_categories();
$tags_array = $media->get_tags();

if( $_SERVER['SERVER_NAME'] == "charlie.coherent.com" ){
	$direct_link = "https://pocmarcomgolfstorage.blob.core.windows.net/" . $Folder . "/" . $SeoUrl;
}else{
	$direct_link = "https://content.coherent.com/" . $Folder . "/" . $SeoUrl;
}

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Add Media</h2>
        </div>

        <div class="page-contents">


	<form id="edit-media" name="edit-media" action="../_media_manager/controllers/index.php" method="post" enctype="multipart/form-data">
	
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Title">Media Title</label>
			</div>

			<div class="form-col-input">
				<input id="Title" name="Title" type="text" value="<?php echo $Title; ?>" placeholder="Enter Product Name" required />
			</div>
		</div>
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Description">Description</label>
			</div>
			<div class="form-col-input">
				<textarea class="form-control" cols="80" id="Description" name="Description" rows="5" placeholder="Enter Description"><?php echo $Description; ?></textarea>                 
			</div>
		</div>
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Category">Category(s)  &nbsp; <em>- separate categories by commas</em></label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Category" name="Category" type="text" value="<?php print $Category; ?>" />
			</div>
		</div>			
		
		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="Tags">Tag(s)  &nbsp; <em>- separate tags by commas</em></label>
			</div>
			<div class="form-col-input">
				<input class="form-control text-box single-line" id="Tags" name="Tags" type="text" value="<?php print $Tags; ?>" />
			</div>
		</div>		

		<div class="form-row">
			<div class="form-col-label">
				<label class="control-label" for="uploadImage">Media</label>
			</div>			
			<div class="form-col-input">
				<input id="file_upload" type="file" name="file_upload"/>
				<div id="preview">Current File: <a href="<?php print $direct_link;?>" target="_blank"><?php print $direct_link;?></a></div><br>
				<div id="upload_notification"></div>
			</div>		
		</div>
		
		<input type="submit" value="Save">
		<input type="button" id="clear_add_form" value="Reset">
		<input type="hidden" name="Type" value="Document">
		<input type="hidden" name="action" value="update">
		<input type="hidden" name="MediaID" value="<?php print $MediaID; ?>">
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
		var tags = [<?php print trim($tags_list);?>];
		
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