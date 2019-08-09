
var edit_columns = [
        { data : 'specs', sTitle: "Specs", sWidth: '30%'},
        { data: 'html', sTitle: "Values", sWidth: '70%'}
    ],
    edit_data = [];

var id = "";

var field_name_array = [
	"ACTIVE_AREA",
	"ACTIVE_AREA_DIAMETER",
	"APPLICATIONS",
	"BEAM_DIAMETER",
	"MATERIALS",
	"MATERIAL_THICKNESS",
	"MAX_ENERGY",
	"MAX_WORK_AREA",
	"MEASUREMENT_TYPE",
	"MIN_ENERGY",
	"MOTION_TYPE",
	"Name",
	"OPERATIONMODE",
	"PC_INTERFACE",
	"PIXEL_SIZE",
	"POWER",
	"PRECISION",
	"PULSE_CW",
	"PULSE_WIDTH",
	"REPETITION_RATE_MAX",
	"TECHNOLOGY",
	"UNCERTAINTY",
	"URL",
	"WAVELENGTH"
];

var input_validation_map = {
    "ACTIVE_AREA":{
	    "pattern": "placeholder='mm'",
	    "val_message": "This is a validation message for ActiveArea",
		"unit_of_measurement":"mm"
	},
    "ACTIVE_AREA_DIAMETER":{ // aka Aperture Size
	    "pattern": "placeholder='mm'",
	    "val_message": "This is a validation message for Aperture Size",
		"unit_of_measurement":"mm"
	},
    "APPLICATIONS":{
    	"pattern": "",
    	"val_message": "This is a validation message for APPLICATIONS",
		"unit_of_measurement":""
    },
    "BEAM_DIAMETER":{
    	"pattern": "pattern='^[+]?[0-9]+\.[0-9]+$' placeholder='mm'", // matches floating numbers
    	"val_message": "Must be a positive decimal number",
		"unit_of_measurement":"mm"
	},
    "COOLING_METHOD":{
    	"pattern": "placeholder='mm'",
    	"val_message": "This is a validation message for Cooling Width",
		"unit_of_measurement":"mm"
    },
    "MATERIAL_THICKNESS":{
    	"pattern": "placeholder='mm'",
    	"val_message": "This is a validation message for Material Thickness",
		"unit_of_measurement":"mm"
    },
    "MAX_ENERGY":{
    	"pattern": "placeholder='J'",
    	"val_message": "This is a validation message for Max Energy",
		"unit_of_measurement":"J"
    },
    "MAX_POWER":{
    	"pattern": "placeholder='mW'",
    	"val_message": "This is a validation message for Max Power",
		"unit_of_measurement":"mW"
    },
    "MAX_WORK_AREA":{
    	"pattern": "placeholder='in/mm'",
    	"val_message": "This is a validation message for Max Work Area",
		"unit_of_measurement":"in/mm"
    },
    "MEASUREMENT_TYPE":{
    	"pattern": "",
    	"val_message": "This is a validation message for MEASUREMENT_TYPE",
		"unit_of_measurement":""
    },
    "MIN_ENERGY":{
    	"pattern": "placeholder='J'",
    	"val_message": "This is a validation message for Min Energy",
		"unit_of_measurement":"J"
    },
    "MOTION_TYPE":{
    	"pattern": "",
    	"val_message": "This is a validation message for OPERATIONMODE",
		"unit_of_measurement":""
    },
    "Name":{
    	"pattern": "",
    	"val_message": "",
		"unit_of_measurement":""
    },
    "OPERATIONMODE":{
    	"pattern": "",
    	"val_message": "",
		"unit_of_measurement":""
    },
    "PC_INTERFACE":{
    	"pattern": "",
    	"val_message": "This is a validation message for PC_INTERFACE",
		"unit_of_measurement":""
    },
    "PIXEL_SIZE":{
    	"pattern": "placeholder='&#181;m'",
    	"val_message": "This is a validation message for Pixel Size",
		"unit_of_measurement":"&#181;m"
    },
    "POWER":{
    	"pattern": "pattern='^[+]?[0-9]+\.[0-9]+$' placeholder='mW'",
    	"val_message": "Must be a positive decimal number",
		"unit_of_measurement":"mW"
    },
    "POWER_MAX":{
    	"pattern": "placeholder='mW'",
    	"val_message": "This is a validation message for Min Power",
		"unit_of_measurement":"mW"
    },
    "POWER_MIN":{
    	"pattern": "placeholder='mW'",
    	"val_message": "This is a validation message for Min Power",
		"unit_of_measurement":"mW"
    },	
    "PRECISION":{
    	"pattern": "placeholder='&#181;m'",
    	"val_message": "This is a validation message for Precision",
		"unit_of_measurement":"&#181;m"
    },
    "PULSE_CW":{
    	"pattern": "placeholder='ns'",
    	"val_message": "This is a validation message for PULSE_CW",
		"unit_of_measurement":"ns"
    },
    "PULSE_WIDTH":{
    	"pattern": "placeholder='ns'",
    	"val_message": "This is a validation message for Pulse Width",
		"unit_of_measurement":"ns"
    },
    "PULSE_WIDTH_MAX":{
    	"pattern": "placeholder='&#181;m'",
    	"val_message": "This is a validation message for Pulse Width",
		"unit_of_measurement":"&#181;m"
    },
    "REPETITION_RATE_MAX":{
    	"pattern": "placeholder='kHz'",
    	"val_message": "This is a validation message for Repetition Rate",
		"unit_of_measurement":"kHz"
    },
    "TECHNOLOGY":{
    	"pattern": "",
    	"val_message": "This is a validation message for PULSE_CW",
		"unit_of_measurement":"ns"
    },
    "UNCERTAINTY":{
    	"pattern": "placeholder='%'",
    	"val_message": "This is a validation message for Calibration Uncertainty",
		"unit_of_measurement":"%"
    },
    "WAVELENGTH":{
    	"pattern": "placeholder='nm'",
    	"val_message": "This is a validation message for Wavelength",
		"unit_of_measurement":"nm"
    },
}

function makeTable() {
    $('#edit').DataTable({
        stateSave: true,
        keys: false, // disabled because it was providing spreadsheet like keyboard navigation; the tab wasn't working a expected
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
		var valid = this.checkValidity();

		// Test if input is valid before updating database
		if(valid){
			$(this).removeClass("error");
			$(this).next(".val_message").removeClass("val_message_selected");

			input_id       = $(this).attr("id");
			new_spec_value = $('#'+ input_id + '').val();
			user = $("#ee-user").val();

			// ajax call to save data
			$.ajax({
				method:"POST",
				url:"/api/admin.php",
				data:{action:"admin_save",id:id, spec_name:input_id, spec_value:new_spec_value, user:user},
				success: function(data){
					console.log(data);
				},
				dataType: "json"
			}).done(function(  ) {
				//alert( "Data Saved: ");
			  });
		}else{
			$(this).addClass("error");
			$(this).next(".val_message").addClass("val_message_selected");
		}
	});	
}

function load_product(id){
	$.post("/api/?action=get-product-detail",{id:id}, function( json ){
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
			spec_name_uom = spec_name + "_uom";
			
			if(key.trim() == "OPERATIONMODE"){
				full_label = "Mode";	
			}

			if(value === 'undefined' || (typeof value === 'undefined') || value == 'null'){
				value = '';
			}
			
		
			
			if(typeof input_validation_map[key.trim()] !== "undefined"){
				// Determine input pattern based on key name.
				var input_pattern = input_validation_map[key.trim()]["pattern"] == "" ? "" : input_validation_map[key.trim()]["pattern"];

				// Determine input validation message based on key name.
				var input_val_message = input_validation_map[key.trim()]["val_message"] == "" ? "" : input_validation_map[key.trim()]["val_message"];

				// Determine input unit of measurement based on key name.
				var input_unit_of_meas = input_validation_map[key.trim()]["unit_of_measurement"] == "" ? "" : '&nbsp;&nbsp;( ' + input_validation_map[key.trim()]["unit_of_measurement"] + ' )';

				switch(key.trim()){
					case "TECHNOLOGY":
						input_html = '<td><select class="spec_input" name="' + spec_name + '" id="' + spec_name + '"><option selected>'+value.Product_Attribute_Value+'</option><option>Ultrafast Oscillators</option><option>DPSS</option></select></td>';
						break;
					case "WAVELENGTH":
						input_html = '<td><select class="spec_input" name="' + spec_name + '" id="' + spec_name + '">';
						input_html  += '<option selected>'+ value.Product_Attribute_Value +'</option><option>Deep UV</option><option>UV - Visible</option><option>UV</option><option>Violet</option></select>';	
						input_html += '</td>';
						break;
					case "Name":
						input_html = '<td><span>' + value + '</span></td>';
						break;
					case "POWER":
					case "PULSE_WIDTH":
					case "REPETION_RATE":
					case "ENERGY":
						if( key.trim() == "POWER" ){
							uom_array = ["mW","W","kW"];
						}else if( key.trim() == "PULSE_WIDTH" ){
							uom_array = ["fs","ps","ns","us","ms","cw"];
						}else if( key.trim() == "REPETION_RATE" ){
							uom_array = ["Hz","kHz","MHz"];
						}else if( key.trim() == "ENERGY" ){
							uom_array = ["micro_J","mJ","J"];
						}
						
						//if( value.Product_Attribute_Value === 'undefined' ){
						if(value.Product_Attribute_Value === 'undefined' || (typeof value.Product_Attribute_Value === 'undefined') || value.Product_Attribute_Value == 'null'){
							prod_attribute_value = "";
						}else{
							prod_attribute_value = value.Product_Attribute_Value;
						}
						input_html = '<td><input class="spec_input" type="text" value="' + prod_attribute_value + '" name="' + spec_name + '" id="'+ spec_name +'">';
						input_html += '<select class="spec_input" name="' + spec_name_uom + '" id="' + spec_name_uom + '">';

						for (var i = 0; i < uom_array.length; i++) {
							if( value.Unit_Measurement == uom_array[i] ){
								input_html += '<option selected>' + uom_array[i] + '</option>';
							}else{
								input_html += '<option>' + uom_array[i] + '</option>';
							}
						}
						//input_html  += '<option selected>mW</option><option>W</option><option>kW</option></select>';	
						input_html += '</td>';
						break;
											
					default:
						input_html = '<td><input type="text"' + input_pattern + 'value="' + value.Product_Attribute_Value + '" class="spec_input" name="' + spec_name + '" id="' + spec_name + '"><div class="val_message">' + input_val_message + '</div></td>';
						break
				}
				
				edit_data.push({
                "specs" :"<span>" + full_label + " " + input_unit_of_meas + "</span>",
                "html" : input_html
				});
			}
		});
		
		makeTable();

		bind_edit_form_inputs(id);

		$('.submit_edit').show();
	},"json");
}

$(document).ready(function () {
    $('.edit-content').hide();

	id = getUrlVars()["id"];

	if(id !== 'undefined'){ console.log(id);
		load_product(id);
	}
})