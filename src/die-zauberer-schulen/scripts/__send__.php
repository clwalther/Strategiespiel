<?php

include "../../scripts/global.php";
include "./general.php";
include "./buildings.php";
include "./students.php";
include "./teachers.php";

$general = new General();
$buildings = new Buildings();
$students = new Students();
$teachers = new Teachers();


foreach($_POST as $keys => $value) {
    // LEXER
    // $value is always string
    switch($keys)
    {
        // general
        case "general_reset":
            $general->reset();
            break;

        case "general_start":
            $general->start();
            break;

        case "general_pause":
            $general->pause();
            break;

        case "general_change_name":
            $general->change_name($value); // value: id;name
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
        case "set_teacher_advanced":
            $teachers->set_advanced($value);

        case "set_teacher_base":
            $teachers->set_base($value); // value: subject(string);skill([1-7]);value(int)
    }
}

$database->close();

?>
