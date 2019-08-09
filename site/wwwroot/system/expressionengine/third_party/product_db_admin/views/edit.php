<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $variable= '<div class="sub-content">
                <button id="back_button">Back</button>
                <div class="loader edit_loader"></div>
                <div class="dataTables_scroll">
                    <form name="edit">
                        <table id="edit" class="table table-striped" cellspacing="0" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </form>
                </div>
                <button class="submit_edit" id="submit_edit">Submit</button>
            </div>';
    echo $variable;

?>
