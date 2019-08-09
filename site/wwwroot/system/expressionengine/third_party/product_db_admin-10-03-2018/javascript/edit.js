
var edit_columns = [
        { data : 'specs', sTitle: "<th>Specs</th>", sWidth: '30%'},
        { data: 'html', sTitle: "<th>1</th>", sWidth: '70%'}
    ],
    edit_data = [];

var id = "";

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
        "pageLength": 50
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
		//product_id = $().val();
		console.log("Product id:" + id);
		$.ajax({
			method:"POST",
			url:"/api/admin.php",
			data:{action:"admin_save",id:id, spec_name:input_id, spec_value:new_spec_value},
			success: function(data){
				console.log(data);
			},
			dataType: "json"
		}).done(function(  ) {
			//alert( "Data Saved: ");
		  });
		//$.post("/api/admin.php",{action:"admin_save",id:id, spec_name:input_id, spec_value:new_spec_value});
	});	

}

function load_product(id){

	$.post( "/api/?action=get-product-detail",{id:id}, function( json ) {
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
			if( key.trim() == "Created_Datetime" || key.trim() == "Modified_Datetime" || key.trim() == "ID"){
				input_html = '<td>'+value+'</td>';
			}else if( key.trim() == "Show_On_Nav" ){
				if( value == 1){
					input_html = '<td><input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '" checked> <label for="' + spec_name + '">Yes</label>';
					input_html += '<input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '"><label for="' + spec_name + '">No</label></td>';
				}else{
					input_html = '<td><input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '"> Yes';
					input_html += '<input type="radio" class="spec_input" value="'+ value + '" name="' + spec_name + '" id="' + spec_name + '" checked> No</td>';
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
			
			edit_data.push({
                "specs" :full_label,
                "html" : input_html
			});
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