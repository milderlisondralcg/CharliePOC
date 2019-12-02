


$(document).ready(function (e) {
	
	var controller = "../_admin_mm/controllers/";
	
 $("#add-media").on('submit',(function(e) {
	 
  e.preventDefault();
  $.ajax({
         url: controller,
	   type: "POST",
	   data:  new FormData(this),
	   contentType: false,
	   cache: false,
	   processData:false,
	   dataType: "json",
	   beforeSend : function(){
		$("#err").fadeOut();
		$("#upload_notification").html("Processing...please standby");
	   },
	   success: function(data){ console.log(data.result);
			if(data.result == 'invalid'){
			 // invalid file format.
			 $("#upload_notification").html("Invalid File !").fadeIn();
			} else if(data.result == 'duplicate title') {
				$("#upload_notification").html("There is already an entry with that Title.").fadeIn();
			} else if( data.result == 'upload error' ){
				$("#upload_notification").html("A problem occurred while trying to store the file into Azure Storage. Please contact the Marcom Web department.").fadeIn();
			} else {
			 $("#add-media")[0].reset();
			 
			 var message = "";
			 message = "Media has been successfully uploaded<br>";
			 message += "Web page URL: " + data.direct_url + "<br>";
			 //message += "SEO URL: " + data.processed_url;
			 $("#upload_notification").html(message);
			}
		},
		error: function(e){
			$("#err").html(e).fadeIn();
		}          
    });
 }));

	$("#clear_add_form").on("click",function(){
		form_id = $(this).parent().attr("id");
		document.getElementById(form_id).reset();
		if( form_id == "add-media" ){
			$("#upload_notification").html("");
		}
		
	});
	
 $("#edit-media").on('submit',(function(e) {
	 
  e.preventDefault();
  $.ajax({
         url: controller,
	   type: "POST",
	   data:  new FormData(this),
	   contentType: false,
	   cache: false,
	   processData:false,
	   dataType: "json",
	   beforeSend : function(){
		$("#err").fadeOut();
		$("#upload_notification").html("Processing...please standby");
	   },
	   success: function(data){ console.log(data.message);
		   $("#upload_notification").html(data.message).fadeIn();
		   /*
			if(data.result == 'invalid'){
			 // invalid file format.
			 $("#upload_notification").html(data.message).fadeIn();
			} else if(data.result == 'invalid file format'){
				$("#upload_notification").html(data.message).fadeIn();
			} else if(data.result == 'duplicate title') {
				$("#upload_notification").html("There is already an entry with that Title.").fadeIn();
			} else {
			//$("#edit-media")[0].reset();
			 
			 var message = "";
			 message = "Your changes have been saved<br>";
			 $("#upload_notification").html(message);
			}
			*/
		},
		error: function(e){
			$("#err").html(e).fadeIn();
		}          
    });
 }));

 
 // end doc ready
 
 
});