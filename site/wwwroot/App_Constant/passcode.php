<html>
<head>
<script src="../_admin_mm/includes/js/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="contact-cont">
  
  <div id="notification">Please enter your email address and the passcode given to you by our customer service rep.</div>
  
  <div class="contact-form">
  <form id="optoskand_login_form" name="optoskand_login_form" method="post" enctype="multipart/form-data">
    <div class="support_form" style="max-width:100%;width:100%">
      <div id="form-messages"></div>
      <div class="form-control">
        <label for="Company">Email *</label>
        <input id="f22"  type="email" name="f22" maxlength="100"  value="" required>
      </div>
      <div class="form-control">
        <label for="address">Passcode *</label>
        <input id="f33" type="Text" name="f33" maxlength="200"value="" required>
      </div>
      <div class="form-control">
		<input type="hidden" name="f11" value="check">
        <input type="submit" value="Submit"  name="f44">
      </div>
    </div>
	</form>
	</div>
	
	
  
</div>


<script>
	$(document).ready(function(){
	
		
	});

		$("#optoskand_login_form").submit(function(event){
			var form_data = $("#optoskand_login_form").serialize();
			var controller = "/App_Constant/mfa";
			console.log(form_data);
	
			  $.ajax({
					url: controller,
				   type: "GET",
				   data:  form_data,
				   contentType: false,
				   cache: false,
				   processData:false,
				   dataType: "json",
				   success: function(data){
						if(data.result == true){
							alert("Success. The list of media for Optoskand should be displayed");
						}else{
							alert("The key submitted is invalid. Please check your email.");
						}							

					},
					error: function(e){
						console.log(e);
						//$("#err").html(e).fadeIn();
					}          
				});
			event.preventDefault();

		});
		
</script>
</body>
</html>