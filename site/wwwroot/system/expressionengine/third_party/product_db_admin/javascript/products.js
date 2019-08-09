var all_components = ["Components", "all_components_table"],
    all_lasers = ["Lasers", "all_lasers_table"],
    all_lmc = ["Laser Measurement", "all_lmc_table"],
    all_tools_systems = ["Tools & Systems", "all_tools_systems_table"],
    product_data = [],
    product_names = [
        { data: 'name', sTitle: '<th>Product Name</th>' }
    ];
	
var product_category = "";
var product_table = "";

function buttons() {
    $('#submit_edit, #back_button').on('click', function() {
        $('.edit-content').hide();
        $('.sub-content-home').show();
    })
}

function open_edit_page(id) {
    $('.sub-content-home').hide();
    $.ajax({
        async: true,
        url: '/system/expressionengine/third_party/product_db_admin/views/edit.php?id=' + id,
        type: 'POST',
        dataType: 'text',
        success: function (variable) {
            $('#back_button').hide();
            $('.edit-content').html(variable);
            load_product(id);
            $('.edit-content').show();
            buttons();
        },
        error: function () {
            alert("error");
        }
    });
}

function show_products() {
    var product_names = [
        { data: 'name', sTitle: 'Product Name' }
    ];
	
	$('#' + product_table).DataTable({
        "processing": true,
        stateSave: true,
        keys: true,
        select: true,
        destroy: true,
        bFilter: true,
        "aaData": product_data,
        "aoColumns" : product_names,
        "ordering": false,
        "pageLength":100
    }); 
    
    jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');
}

// target category tabs
// return the required products for given category
function get_products( elem ){

	if( elem.indexOf("components") >= 0){
		product_category = "components";
		product_table = "all_components_table";
		template_data = all_components;
		html_class = "all_components";
	}else if( elem.indexOf("lasers") >= 0 ){
		product_category = "lasers";
		product_table = "all_lasers_table";
		template_data = all_lasers;
		html_class = "all_lasers";
	}else if( elem.indexOf("lmc") >= 0 ){
		product_category = "lmc";
		product_table = "all_lmc_table";
		template_data =  all_lmc;
		html_class = "all_lmc";
	}else if( elem.indexOf("tools-systems") >= 0 ){
		product_category = "tools_systems";
		product_table = "all_tools_systems_table";
		template_data = all_tools_systems;
		html_class = "all_tools";
	}
	
	product_data = [];
	
    $.getJSON("/api/?action=get-admin-products-category", { category:product_category }, function(json) {
		
		$.each( json, function( key, value ) {
			product_data.push({
				"name" : '<a onclick="open_edit_page(\'' + value.ID +'\')">' + value.Name + '</a>'
			}); 		  
		});

		$.ajax({
			url: '/system/expressionengine/third_party/product_db_admin/views/products_template.php',
			type: 'POST',
			dataType: 'text',
			data: {templates:JSON.stringify(template_data)},
			success: function (variable) {
				$('.' + html_class).html(variable);
				show_products();
			},
			error: function(){
				alert("error");
			}
		});		

    });	
	
}