<!--    <script type="text/javascript" src="<?php echo $path?>assets/DataTables/datatables.min.js"></script>-->

<!--
    <script src="assets/json/json_example.json"></script> 
    <script src="<?php echo $path?>js/bootstrap.min.js"></script>
    <script src="<?php echo $path?>js/sisyphus.min.js"></script>-->
<!--
    <script src="<?php echo $path?>javascript/main.js"></script>
    <script src="<?php echo $path?>javascript/products.js"></script>
    <script src="<?php echo $path?>javascript/edit.js"></script>
    <script src="<?php echo $path?>javascript/close.js"></script>
-->


	
	<script>
	$(document).ready(function() {
		var products_list = $('#home_list').DataTable( {
			ajax: {
				url: 'https://charlie.coherent.com/api/?action=products',
				dataSrc: ''
			},
			columns: [
				{ "title": "ID",data:"ID" },
				{ "title":"Name",data: "Name" },
				{ "title":"Technology",data: "attributes.TECHNOLOGY" },
				{ "title":"Wavelength",data: "attributes.WAVELENGTH" },
				{ "title":"Power",data: "attributes.POWER" },
				{ "title":"Mode",data: "attributes.OPERATIONMODE" },
				{ "title":"Pulse Width",data: "attributes.PULSE_WIDTH" },
			]
		} );
		
		$('#home_list tbody').on('click', 'tr', function () {
			console.log(this.data);
			console.log('API row values : ', products_list.row(this).data());
		} );
		
		$(".category-tab").click(function(){
			var tab = $(this).attr("id");
			get_products(tab);
		});
	
	});
	
	</script>
</body>

</html>