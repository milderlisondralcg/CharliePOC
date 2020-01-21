<?php
include('includes/header.php');

?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Publish JSON</h2>
        </div>

        <div class="page-contents">
		
		<input type="button" name="optoskand" id="optoskand" class="publish_button" value="Optoskand">
		
		<input type="button" name="PDF" id="PDF" class="publish_group" value="PDF">
	
	</div>
</div>
	<script>
		var controller = "../_admin_mm/controllers/business.php";
		
		$( ".publish_button" ).on("click", function(){
			console.log($(this).attr("id"));
			product_family = $(this).attr("id");
			$.post(controller,{action:"publish",product_family:product_family});
		});
		
		var group_controller = "../_admin_mm/controllers/group.php";
		$( ".publish_group" ).on("click", function(){
			console.log($(this).attr("id"));
			product_group = $(this).attr("id");
			$.post(group_controller,{action:"publish",product_group:product_group});
		});	  
	</script>

<?php
include('includes/footer.php');
?>