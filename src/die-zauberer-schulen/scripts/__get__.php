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


$array = [
    "general" => [
        "teams" => $general->get_teams(),
        "times" => $general->get_times()
    ],
    "labour" => [

    ],
    "school_admin" => [
        "students" => $students->get_requirments(),
        "teachers" => $teachers->get_requirments(),
        "buildings" => $buildings->get_requirments()
    ]
];

echo json_encode($array);

$database->close();

?>
