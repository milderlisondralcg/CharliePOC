<div class="contact-cont">
  <div class="contact-form">
    <div id="contact_form" class="support_form" style="max-width:100%;width:100%">
      <div id="form-messages"></div>
      <div class="form-control">
        <label for="Company">email *</label>
        <input id="f22"  type="email" name="f22" maxlength="100"  value="" required>
      </div>
      <div class="form-control">
        <label for="address">key *</label>
        <input id="f33" type="Text" name="f33" maxlength="200"value="" required>
      </div>
      <div class="form-control">

        <input type="submit" value=" Submit "  name="f44" onClick="mfa()" >
		
      </div>
    </div>
  </div>
</div>


<script>
function mfa(){
	$.ajax({
		url: "/mfa", 
		type: "GET",
		data: { "f11":"check","f22": document.getElementById('f22').value, 
			   "f33": document.getElementById('f33').value,
			  	"f44": new Date().getTime() },
		dataType: "html", cache: false, beforeSend: function() {    
			console.log("Processing...");
		},
		success: 
		function(data){
			if(data == "1"){
				document.getElementById("contact_form").innerHTML = "An email with login instructions has been sent to your email";
			}else{
				console.log(data);
				//window.location.reload(true); 
				
			}
		}
	});
}
</script>