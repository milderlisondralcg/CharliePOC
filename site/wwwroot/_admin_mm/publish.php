<?php
include('includes/header.php');

spl_autoload_register('mmAutoloader');

function mmAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

$media = new Media();
$groups = $media->get_groups();

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Publish Group Portal</h2>
        </div>

        <div class="page-contents">
		
		<input type="button" name="optoskand" id="optoskand" class="publish_button" value="Optoskand"><br/><br/>
		
	<?php
		if($groups != 0){
			$groups_array = array();
			foreach($groups as $group){
				$temp_group_array = explode(",", $group['group']);
				foreach($temp_group_array as $item){
					if(isset($item) && $item != ""){
						$groups_array[] = trim(strtolower($item));
					}					
				}
			}
			asort($groups_array);
		}
		$groups_array_cleaned = array_unique($groups_array);
		foreach($groups_array_cleaned as $group_item){
		?>
			<input type="button" name="<?php print $group_item; ?>" id="<?php print $group_item; ?>" class="publish_group" value="<?php print $group_item; ?>"><br/>
			
		<?php
		}			
	?>
	
	</div>
</div>
	<script>
		var controller = "../_admin_mm/controllers/business.php";
		
		$( ".publish_button" ).on("click", function(){
			product_family = $(this).attr("id");
			$.post(controller,{action:"publish",product_family:product_family});
		});
		
		var group_controller = "../_admin_mm/controllers/business.php";
		$( ".publish_group" ).on("click", function(){
			console.log($(this).attr("id"));
			product_group = $(this).attr("id");
			$.post(group_controller,{action:"publish",product_group:product_group,type:"group"});
		});	  
	</script>

<?php
include('includes/footer.php');
?>