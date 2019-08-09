<?php include("templates/header.php");?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#homepage">Homepage</a></li>
        <li><a data-toggle="tab" href="#new_product">Add New Product</a></li>
    </ul>

    <div class="modal editModal" id="editModal">
        <div class="modal-content">
            <span class="close" id="editClose">&times;</span>
            <br>
            <div class="content editContent">

            </div>
        </div>
    </div>

    <div class="tab-content">
        <div id="homepage" class="tab-pane fade in active">
            <div class="sub-content sub-content-home">
                <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs nav-tabs-responsive" role="tablist">  
                        <li role="presentation"> 
                            <a href="#all_components" role="tab" id="all-components-tab" class="category-tab" data-toggle="tab" aria-controls="all_components"> <span class="text">Components</span></a>
                        </li>
                        <li role="presentation"> 
                            <a href="#all_lasers" role="tab" id="all-lasers-tab" data-toggle="tab" class="category-tab" aria-controls="all_lasers"> <span class="text">Lasers</span></a>
                        </li>                        
                        <li role="presentation"> 
                            <a href="#all_lmc" role="tab" id="all-lmc-tab" class="category-tab" data-toggle="tab" aria-controls="all_lmc"> <span class="text">Laser Measurement</span></a>
                        </li>
                        <li role="presentation"> 
                            <a href="#all_tools" role="tab" id="all-tools-systems-tab" class="category-tab" data-toggle="tab" aria-controls="all_tools"> <span class="text">Tools and Systems</span></a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">        
                        <div role="tabpanel" class="tab-pane fade all_components" id="all_components" aria-labelledby="all-components-tab">
                            <div class="loader"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_lasers" id="all_lasers" aria-labelledby="all-lasers-tab">
                            <div class="loader"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_lmc" id="all_lmc" aria-labelledby="all-lmc-tab">
                            <div class="loader"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_tools" id="all_tools" aria-labelledby="all-tools-systems-tab">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="edit-content" style="display:none;">
                <?php require("edit.php")?>
            </div>
        </div>
        <div id="new_product" class="tab-pane fade">
            <?php require("form.php")?>
        </div>
    </div>
<?php include("templates/footer.php");?>