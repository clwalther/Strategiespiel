<?php

include "../../scripts/global.php";
include "./general.php";
include "./buildings.php";

$general = new General();
$buildings = new Buildings();

foreach($_POST as $keys => $value) {
    // LEXER
    // $value is always string
    switch($keys)
    {
        // general
        case "reset":
            $general->reset();
            break;

        case "start":
            $general->start();
            break;

        case "pause":
            $general->pause();
            break;

        // ministry of labour

        // ministry of school administration
        // buildings
        case "acitvate_building_id":
            $buildings->activate($value);
            break;

        case "deacitvate_building_id":
            $buildings->deactivate($value);
            break;

        // students
        case "check_out_student":
            $students->check_out($value);
            break;
        // teachers
    }
}

?>
