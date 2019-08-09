<?php include("templates/header.php");?>
            <div class ="wrapper">
                <div class="main-content">
                    <div class="header">
                        <h2>Admin Form: Add New Specifications</h2>
                    </div>
                    <div class="sub-content">
                        <div class="form">
                            <form action="database.php" method="POST">
                                <div class="input_field">
                                    <label for="spec_name">Specification</label>
                                    <input type="text" id="spec_name" name="spec_name">
                                </div>
                                <div class="input_field">
                                    <label for="category">Category</label>
                                    <input type="text" id="category" name="category">
                                </div>
                                <div class="input_field">
                                    <label for="sub_category">Sub Category</label>
                                    <input type="text" id="sub_category" name="sub_category">
                                </div>
                                <div class="input_field">
                                    <label for="units">Units</label>
                                    <input type="text" id="units" name="units">
                                </div>
                                <div class="input_field">
                                    <input type="submit" value="Submit">
                                </div>
                                <div class="clear"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<?php include("templates/footer.php");?>
