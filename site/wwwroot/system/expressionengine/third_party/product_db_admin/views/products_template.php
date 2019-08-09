<?php
    $array = json_decode($_POST['templates']);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $variable = '<div class="dataTables_scroll">
            <table id="' . $array[1] . '" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>';
    echo $variable;
?>