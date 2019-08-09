<html>
<head>
<link rel="stylesheet" href="../assets/css/normalize.min.css?v=148">
<link rel="stylesheet" href="../assets/css/style.min.css?v=148">
<script src="../_admin_mm/includes/js/jquery-3.3.1.min.js"></script>
</head>
<body>


<div id="media"></div>

<script>
	$(document).ready(function(){
		
		$("#media").load("optoskand.html");
		/* 
		var json_file = "optoskand.json";
		var url_downloand_icon = "";
		

		$.getJSON(json_file, function(data){
			var html_display = "";
			html_display += '<div class="summary">';
			$.each( data, function( key, val ) {

				html_display += "<h2>" + key + "</h2>";
				html_display += '<div class="content-container">';
				html_display += '<div class="resource-content">';
				html_display += '<div class="service-doc-cont">';
				
				html_display += '<div class="resource-item">';
				html_display += '<div class="form_accordion" class="accordion-form">';
				html_display += '<div class="toggler">';
				html_display += '<div class="icon"> <img align="absmiddle" src="/assets/site_images/minus.png" alt="Toggle" direction="down"> </div>';


					$.each( val, function( k, items ) {
						html_display += '<div class="related-title resource-prod"><h2>' + k + '</h2></div>';
						html_display += '<div class="resource-container" style="display:block;>';
						html_display += '<div class="clear"></div>';
						
						// list of items within a specific Tag
						$.each( items, function( item_key, item_value ) {
							console.log(item_value.MimeType);
							switch(item_value.MimeType){
								case "application/pdf":
									url_download_icon = '/assets/site_images/small-pdf-icon.png';
									break;
								case "application/x-zip":
									url_download_icon = '/assets/site_images/zip-icon.png';
									break;
								default:
									url_download_icon = '/assets/site_images/small-dl-icon.png';
									break;
							}
							
							html_display += '<div class="support-doc-box resource-center-support-doc-box ">';
							html_display += '<div class="thumb">';
							html_display += '<a href="###" target="_blank">';
							html_display += '<img src="' + url_download_icon + '" alt="Coherent Download"></a>';
							html_display += '</div>';
							//html_display += '</div>';
							html_display += '<div class="support-box-cont"><div class="title"><a href="###" target="_blank">' + item_value.Title + '</a></div></div>';
							html_display += '<div class="summary"></div>';
							
							console.log(url_downloand_icon);							
						});
						// End list of items within a specific Tag
						html_display += '</div><hr></div>';
				
					});

				html_display += '</div></div></div></div></div></div>';
				
			  });	
			html_display += '</div>';
			$("#media").html(html_display)			  
		}); */
		
	});
		
</script>
</body>
</html>