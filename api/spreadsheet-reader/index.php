<html>
<body>
<a href="read-fields-only.php">Read Fields/Columns from Excel file</a><br/><br/>

<a href="read-products.php?set=a" target="_blank">Read Data from Excel for records 1 - 499</a><br/><br/>
<a href="read-products.php?set=b" target="_blank">Read Data from Excel for records 500-999</a><br/><br/>
<a href="read-products.php?set=c" target="_blank">Read Data from Excel for records 1000 - 1499</a><br/><br/>
<a href="read-products.php?set=d" target="_blank">Read Data from Excel for records 1500-1999</a><br/><br/>
<a href="read-products.php?set=e" target="_blank">Read Data from Excel for records 2000-2274</a><br/><br/>
<a href="get-applications.php?set=a target="_blank"">Populate pd_products_applications table</a><br/><br/>

<h2>Read Attributes</h2>
<a href="read-attributes.php?set=a" target="_blank">File A</a><br/><br/>
<a href="read-attributes.php?set=b" target="_blank">File B</a><br/><br/>
<a href="read-attributes.php?set=c" target="_blank">File C</a><br/><br/>
<a href="read-attributes.php?set=d" target="_blank">File D</a><br/><br/>
<a href="read-attributes.php?set=e" target="_blank">File E</a><br/><br/>

<h2>Read Applications</h2>
<a href="gather-applications.php?set=a" target="_blank">File A</a><br/><br/>
<a href="gather-applications.php?set=b" target="_blank">File B</a><br/><br/>
<a href="gather-applications.php?set=c" target="_blank">File C</a><br/><br/>
<a href="gather-applications.php?set=d" target="_blank">File D</a><br/><br/>
<a href="gather-applications.php?set=e" target="_blank">File E</a><br/><br/>

<h2>Read Categories for each Product From Excel file</h2>
<a href="read-categories.php?set=a" target="_blank">File A</a><br/><br/>
<a href="read-categories.php?set=b" target="_blank">File B</a><br/><br/>
<a href="read-categories.php?set=c" target="_blank">File C</a><br/><br/>
<a href="read-categories.php?set=d" target="_blank">File D</a><br/><br/>
<a href="read-categories.php?set=e" target="_blank">File E</a><br/><br/>

<h2>Gather Product Families From Excel file</h2>
<a href="get-families.php?set=a" target="_blank">File A</a><br/><br/>

<h2>Read Family for each Product From Excel file</h2>
<a href="read-families.php?set=a" target="_blank">File A</a><br/><br/>
<a href="read-families.php?set=b" target="_blank">File B</a><br/><br/>
<a href="read-families.php?set=c" target="_blank">File C</a><br/><br/>
<a href="read-families.php?set=d" target="_blank">File D</a><br/><br/>
<a href="read-families.php?set=e" target="_blank">File E</a><br/><br/>


<br/>
<a href="###" class="read-file add-products" rel="a">Read Data Set A</a><br/><br/>
<a href="###" class="read-file add-products" rel="b">Read Data Set B</a><br/><br/>
<a href="###" class="read-file add-products" rel="c">Read Data Set C</a><br/><br/>
<a href="###" class="read-file add-products" rel="d">Read Data Set D</a><br/><br/>
<a href="###" class="read-file add-products" rel="e">Read Data Set E</a><br/><br/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		
		$(".read-file").click(function(){
			console.log($(this).attr("rel"));
			var process_type = $(this).attr("class");
			var data_set = $(this).attr("rel");
			var endpoint = "create-sql.php?set=" + data_set;
			console.log(endpoint);
			$.ajax({url:endpoint});

		});
		
	});
</script>
</body>

</html>