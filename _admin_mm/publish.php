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
	
	</div>
</div>
	<script>
		var controller = "../_admin_mm/controllers/business.php";
		
		$( ".publish_button" ).on("click", function(){
			console.log($(this).attr("id"));
			$.post(controller,{action:"publish"});
		});
		
	  
	</script>

<?php
include('includes/footer.php');
?>