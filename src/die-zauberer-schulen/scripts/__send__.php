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

foreach($_POST as $key => $values) {
    // converts previously converted key back
    $key = urldecode($key);

    foreach(explode(",", $values) as $value) {
        // converts previously converted value back
        $value = urldecode($value);

        // LEXER
        switch($key)
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
                $general->change_name($value); // value: int(id);string(name)
                break;
            // ministry of labour

            // ministry of school administration
            // buildings
            case "acitvate_building":
                $buildings->activate($value);
                break;

            case "deacitvate_building":
                $buildings->deactivate($value);
                break;

            // students
            case "check_out_student":
                $students->check_out($value);
                break;

            // teachers
            case "set_teacher_advanced":
                $teachers->set_advanced($value);
                break;

            case "set_teacher_base":
                $teachers->set_base($value); // value: subject(string);skill([1-7]);value(int)
                break;
        }
    }
}

$database->close();

?>
