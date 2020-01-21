<?php
include('includes/header.php');
?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Media List</h2>
        </div>

        <div class="page-contents">
            <div>
                <div class="group">
                    <table id="media_list">
                        <thead>
                            <tr>
                                <th>Title</th>
								<th>Folder</th>
                                <th>Category</th>
								<td>Group</td>
                                <th>Description</th>
								<th>Link To File</th>
								<th>Tags</th>
								<th>Last Modified</th>
								<th></th>
								<th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Title</th>
								<th>Folder</th>
                                <th>Category</th>
								<td>Group</td>
                                <th>Description</th>
								<th>Link To File</th>
								<th>Tags</th>
								<th>Last Modified</th>
								<th></th>
								<th></th>
                            </tr>
                        </tfoot>
                        <tbody>
                                <tr>
                                    <td>Title</td>
									<th>Folder</th>
									<td>Category</td>
									<td>Group</td>
									<td>Description</td>
									<td>Link To File</td>
									<td>Tags</td>
									<td>Last Modified</td>
									<td>Action Delete</td>
									<td>Action Edit</td>
                                </tr>
                        </tbody>

                    </table>
                </div>
            </div>
    </div>
</div>


<script>

    $(document).ready(function () {

		//format for the Last Modified Datetime columnDefs
		 $.fn.dataTable.moment( 'MM/DD/YYYY' );
		 
        var mydatatable = $('#media_list').DataTable({
			"ajax": { 
			 "url": "/_admin_mm/controllers/",
			 "type": "POST",
			 "data": {"action":"get_home_list" }
			},
			columns: [
				{ data: 'Title' },
				{ data: 'Folder' },
				{ data: 'Category' },
				{ data: 'Group' },
				{ data: 'Description' },
				{ data: 'LinkToFile' },
				{ data: 'Tags' },
				{ data: 'LastModified'},
				{ data: 'ActionDelete' },
				{ data: 'ActionEdit' }
			],
			"columnDefs": [
				{ className: "action_delete", "targets": [ 8 ] },
				{ className: "action_edit", "targets": [ 9 ] },
				{ className: "links", "targets": [ 5 ] }
			  ],
			"order": [[ 7, "desc" ]] // sort by Last Modified Date, descending
		});
		
		$('#media_list tbody').on( 'click',  'td.action_delete', function () {			
			var media_id = $(this).parent().attr("id");	
				if( confirm("Are you sure you want to Archive this Media asset?") ){
					$.post("/_admin_mm/controllers/",{"MediaID":media_id,"action":"delete"},function(data){
						if(data.result == true){
							$("#"+media_id).remove(); // delete row from data table
							alert("The Media asset has been Archived");
						}else{
							alert("There was a problem Archiving the Media asset. Please notify an administrator.");
						}
					},"json");
				}
		});	
		
		$('#media_list tbody').on( 'click',  'td.action_edit', function () {			
			var media_id = $(this).parent().attr("id");	
			if( $(this).attr("id") == "action_edit" ){
				console.log("redirect");
			}
			window.location.href = "/_admin_mm/edit.php?mediaid=" + media_id;
		});		
	
    });

</script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datatables.min.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/moment.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datetime-moment.js"></script>

<?php
include('includes/footer.php');
?>