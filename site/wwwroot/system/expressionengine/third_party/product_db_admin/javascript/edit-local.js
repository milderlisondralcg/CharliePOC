
var edit_columns = [
        { data : 'specs', sTitle: "Specs", sWidth: '30%'},
        { data: 'html', sTitle: "Values", sWidth: '70%'}
    ],
    edit_data = [];

var id = "";

var units_of_measurement_map = {
    "ActiveArea":"mm",
    "Aperture Size":"mm",
    "Beam Diameter":"mm",
    "Calibration Uncertainty":"%",
    "Cooling Width":"mm",
    "Laser Pulse Width":"&#181;m",
    "Material Thickness":"mm",
    "Max. Energy":"J",
    "Max. Power":"mW",
    "Max. Work Area":"in/mm",
    "Min. Energy":"J",
    "Min. Power":"mW",
    "Pixel Size":"&#181;m",
    "Power":"mW",
    "Precision":"&#181;m",
    "Pulse Width":"ns",
    "Repetition Rate":"kHz",
    "Wavelength":"nm"
};

function makeTable() {
    $('#edit').DataTable({
        stateSave: true,
        keys: true,
        select: true,
        destroy: true,
        bFilter: true,
        "aaData": edit_data,
        "aoColumns" : edit_columns,
        "ordering": false,
        "pageLength": 50,
        "paging": false, // disabled for initial datascrub
        "bFilter": false, // disabled for initial datascrub
        "bInfo": false // disabled for initial datascrub
    });
    
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');
    edit_data = [];
    $('.edit_loader').hide();
    $('#back_button').show();
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}

function bind_edit_form_inputs(id){
	$('.spec_input').on('blur',function(){
		input_id = $(this).attr("id");

		// ajax call to save data
		new_spec_value = $('#'+ input_id + '').val();
		console.log("Product id:" + id);
		user = $("#ee-user").val();
		$.ajax({
			method:"POST",
			url:"/api/admin.php",
			data:{action:"admin_save",id:id, spec_name:input_id, spec_value:new_spec_value, user:user},
			success: function(data){
				console.log(data);
			},
			dataType: "json"
		}).done(function(  ) {
			console.log( "Data Saved: ");
		  });
	});	

}

var field_name_array = ["Name","OPERATIONMODE","TECHNOLOGY","URL",
"WAVELENGTH","TECHNOLOGY","POWER","PULSE_WIDTH",
"APPLICATIONS","MATERIALS","MATERIAL_THICKNESS",
"MOTION_TYPE","MAX_WORK_AREA","PRECISION",
"ACTIVE_AREA_DIAMETER",
"MIN_ENERGY",
"MAX_ENERGY",
"REPETITION_RATE_MAX",
"PC_INTERFACE",
"MEASUREMENT_TYPE",
"UNCERTAINTY",
"BEAM_DIAMETER",
"ACTIVE_AREA",
"PIXEL_SIZE","PULSE_CW"];

function load_product(id){
	$.post( "/api/?action=get-product-detail",{id:id}, function( json ) {
		console.log(json);
		

		// Ensures that Modified Datetime is at the top of the form
		edit_data.push({
		"specs" :"<span>Last Modified</span>",
		"html" : '<td><span>'+json.Modified_Datetime +'</span></td>'
		});		
		
		$.each( json, function( key, value ) {

			key_array = key.split("_");
			var full_label = '';
		
			if(key_array.length > 1){			
				for( i = 0; i < key_array.length; i++ ){
					label_temp = key_array[i].toLowerCase();
					full_label += ' ' + label_temp.charAt(0).toUpperCase() + label_temp.substr(1);
				}
			}else{
				full_label = key.toLowerCase();
				full_label = full_label.charAt(0).toUpperCase() + full_label.substr(1);
			}

			spec_name = "spec_" + key;
			
			if(key.trim() == "OPERATIONMODE"){
				full_label = "Mode";	
			}

			if( value === 'undefined' || (typeof value === 'undefined') || value == 'null'){
				value = '';
			}

			if( field_name_array.indexOf( key.trim()) >= 0 ){
				switch( key.trim() ){
					case "TECHNOLOGY":
						input_html = '<td><select class="spec_input" name="' + spec_name + '" id="' + spec_name + '"><option selected>'+value+'</option><option>Ultrafast Oscillators</option><option>DPSS</option></select></td>';
						break;
					case "WAVELENGTH":
						input_html = '<td><select class="spec_input" name="' + spec_name + '" id="' + spec_name + '">';
						input_html  += '<option selected>'+ value +'</option><option>Deep UV</option><option>UV - Visible</option><option>UV</option><option>Violet</option>';
						input_html += '</select></td>';
						break;
					case "Name":
						input_html = '<td><span>' + value + '</span></td>';
						break;
					default:
						input_html = '<td><input type="text" value="' + value + '" class="spec_input" name="' + spec_name + '" id="' + spec_name + '"></td>';
						break
				}
				
				
				var unit_of_meas = typeof units_of_measurement_map[full_label.trim()] === "undefined" ? "" : '&nbsp;&nbsp;( ' + units_of_measurement_map[full_label.trim()] + ' )';
				
				edit_data.push({
                "specs" :"<span>" + full_label + " " + unit_of_meas + "</span>",
                "html" : input_html
				});
			}

		
			
			
			/*
			if( key.trim() == "Created_Datetime" || key.trim() == "Modified_Datetime" || key.trim() == "ID"){
				input_html = '<td><span>'+value+'</span></td>';
			}else if( key.trim() == "Show_On_Nav" ){
				if( value == 1){
					input_html = '<td><input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" checked> <label for="' + spec_name + '">Yes</label>';
					input_html += '<input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '"><label for="' + spec_name + '">No</label></td>';
				}else{
					input_html = '<td><input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '"> Yes';
					input_html += '<input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" checked> No</td>';
				}
			}else if( key.trim() == "Description"){
				input_html = '<td><textarea class="spec_input" name="' + spec_name + '" id="' + spec_name + '">'+value+'</textarea></td>';
				$('#' + spec_name +'').val(value);
			}else if( key.trim() == "TECHNOLOGY" ){
				input_html = '<td>';
				input_html += '<select><option>Ultrafast Oscillators</option><option>DPSS</option></select>';
				//input_html += '<input type="text" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '">';
				input_html += '</td>';
			}else{
				input_html = '<td><input type="text" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '"></td>';
			}
						
			var unit_of_meas = typeof units_of_measurement_map[full_label.trim()] === "undefined" ? "" : '&nbsp;&nbsp;( ' + units_of_measurement_map[full_label.trim()] + ' )';

			edit_data.push({
                "specs" :"<span>" + full_label + " " + unit_of_meas + "</span>",
                "html" : input_html
			});
			*/
			
		});
		
		makeTable();
		bind_edit_form_inputs(id);	
		$('.submit_edit').show();
			
	},"json");
	
	
	
}

$(document).ready(function () {
    $('.edit-content').hide();
	id = getUrlVars()["id"];
	if( id !== 'undefined'){ console.log(id);
		load_product(id);
	}
	
})