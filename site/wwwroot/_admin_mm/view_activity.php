<?php
include('includes/header.php');
?>
<div class="main-wrapper">
    <div class="contents">
        <div class="heading">
            <h2>Activity</h2>
        </div>

        <div class="page-contents">
            <div>
                <div class="group">
                    <table id="log_entries">
                        <thead>
                            <tr>
								<th>Admin</th>
                                <th>Created</th>
								<th>Action</th>
                                <th>Object</th>
                                <th>Previous Data</th>
								<th>Updated Data</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
								<th>Admin</th>
                                <th>Created</th>
								<th>Action</th>
                                <th>Object</th>
                                <th>Previous Data</th>
								<th>Updated Data</th>
                            </tr>
                        </tfoot>
                        <tbody>
							<tr>
								<th>Admin</th>
								<td>Created</td>
								<td>Action</td>
								<td>Object</td>
								<td>Previous Data</td>
								<td>Updated Data</td>
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
		 
        var mydatatable = $('#log_entries').DataTable({
			"ajax": { 
			 "url": "/_admin_mm/controllers/log_controller.php",
			 "type": "POST",
			 "data": {"action":"get_log_data" }
			},
			columns: [
				{ data: 'Created' },
				{ data: 'Admin' },
				{ data: 'Action' },
				{ data: 'Object' },
				{ data: 'Previous Data' },
				{ data: 'Updated Data' }
			],
			"order": [[ 0, "desc" ]]
		});
		
    });

</script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datatables.min.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/moment.js"></script>
<script src="<?php print DIRECT_TO_FILE_URL; ?>assets/js/datatables/datetime-moment.js"></script>

<?php
include('includes/footer.php');
?>