<?php include("templates/header.php");?>
    <div class="sub-content sub-content-new">
        <button class="templates">Choose A Category</button>
<!--        <button class="btnAddCol" data-test="btnAddCol">Add A Column</button>-->

        <div class="modal tempModal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="add_delete">
                    <button class="add_templates">Add</button>
                </div>
                <br><br>
                <h3>Templates</h3>
                <div class="content">
                    <ul class="nav flex-column tempTab_col" role="tablist">
                    </ul>
                </div>

                <div class="tab-content info" id="info1">
                    <form class="info_more1">
                    </form>
                </div>
            </div>
        </div>

        <div class="dataTables_scroll">
            <form name="product" id="product" method="post" action="test_form.php">
                <table id="new_p" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </form>
            <button class="submit" onclick="onSubmit()" type="submit">Submit</button>
        </div>
    </div>
<?php include("templates/footer.php");?>