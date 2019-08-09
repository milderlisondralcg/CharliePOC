<?php include("templates/header.php");?>
    <div class="wrapper">
        <div class="main-content">
            <div class="header">
                <h2>View Product Data</h2>
            </div>
            <div class="sub-content">
                <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs nav-tabs-responsive" role="tablist">       
                        <li role="presentation"> 
                            <a href="#all_components" role="tab" id="all-components-tab" class="all-components-tab" data-toggle="tab" aria-controls="all_components"> <span class="text">Components</span></a>
                        </li>  
                        <li role="presentation"> 
                            <a href="#all_lasers" role="tab" id="all-lasers-tab" data-toggle="tab" class="all-lasers-tab" aria-controls="all_lasers"> <span class="text">Lasers</span></a>
                        </li>                        
                        <li role="presentation"> 
                            <a href="#all_lmc" role="tab" id="all-lmc-tab" class="all-lmc-tab" data-toggle="tab" aria-controls="all_lmc"> <span class="text">Laser Measurement</span></a>
                        </li>
                        <li role="presentation"> 
                            <a href="#all_tools" role="tab" id="all-tools-systems-tab" class="all-tools-systems-tab" data-toggle="tab" aria-controls="all_tools"> <span class="text">Tools & Systems</span></a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade all_components" id="all_components" aria-labelledby="all-components-tab">
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_lasers" id="all_lasers" aria-labelledby="all-lasers-tab">
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_lmc" id="all_lmc" aria-labelledby="all-lmc-tab">
                        </div>
                        <div role="tabpanel" class="tab-pane fade all_tools" id="all_tools" aria-labelledby="all-tools-systems-tab">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("templates/footer.php");?>