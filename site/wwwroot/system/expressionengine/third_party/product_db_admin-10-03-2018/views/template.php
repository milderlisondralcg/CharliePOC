<?php
    $array = json_decode($_POST['templates']);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $variable = '<button class="'. $array[0] .'">Choose A Template</button>
        <button class="'. $array[1] .'">Add Specs</button>
        <button class="'. $array[2] .'">Add Column</button>
        <button class="'. $array[3] .'">Delete Row</button>

        <div class="modal '. $array[4] .'">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div>
                    <form class="'. $array[5] .'" method="post" onsubmit="'. $array[6] .'">
                        <input list="specs" name="specs" id="search">
                            <datalist id="'. $array[7] .'">
                            </datalist>
                        <input id="'. $array[8] .'" type="submit">
                    </form>
                </div>
                <div class="'. $array[9] .'">
                    <button class="'. $array[10] .'">Add</button>
                    <input class="'. $array[11] .'" type="button" value="Check All">
                    <input class="'. $array[12] .'" type="button" value="Uncheck All">
                    <button class="'. $array[13] . '">Done</button>
                    <p class="message"></p>
                </div>
                <br>
                <h3>Specifications</h3>
                <div class="content">
                    <ul class="nav flex-column '. $array[14] .'" role="tablist">
                    </ul>
                </div>

                <div class="tab-content info" id="'. $array[15] .'">
                    <form class="'. $array[16] .'">
                    </form>
                </div>
            </div>
        </div>

        <div class="modal '. $array[17] .'">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div>
                    <form class="'. $array[18] .'" method="post" onsubmit="' . $array[19] . '">
                        <input list="categories" name="categories" id="searchTemp">
                            <datalist id="' . $array[20] . '">
                            </datalist>
                        <input id ="searchBtn" type="submit">
                    </form>
                </div>
                <div class="add_delete">
                    <button class="'. $array[21].'">Add</button>
                </div>
                <br><br>
                <h3>Templates</h3>
                <div class="content">
                    <ul class="nav flex-column '. $array[22] .'" role="tablist">
                    </ul>
                </div>

                <div class="tab-content info" id="'. $array[23] .'">
                    <form class="'. $array[24] .'">
                    </form>
                </div>
            </div>
        </div>
        
        <div class="dataTables_scroll">
            <form name="'. $array[25] .'" id="'. $array[26] .'" method="post" action="test_form.php">
                <table id="' . $array[27] . '" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="submit" onclick="'. $array[28] .'" type="submit">Submit</button>
            </form>
        </div>';
    echo $variable;
?>