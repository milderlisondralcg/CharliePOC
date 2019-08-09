<?php
    $array = json_decode($_POST['templates']);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');



    $variable = '    <div class="modal '. $array[0] . '">
        <div class="modal-content">
            <span class="close">&times;</span>
            <br>
            <h3>Specifications</h3>
            <div class="content">
                <div class="dataTables_scroll">
                    <form name="'. $array[1] . '">
                        <table id="'. $array[2] . '" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <button class="submit_edit" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
    echo $variable;
?>