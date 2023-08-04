<?php

include "../../scripts/global.php";

include "./general.php";
include "./buildings.php";
include "./students.php";
include "./teachers.php";
include "./labour.php";
include "./prestige.php";

$general = new General();
$buildings = new Buildings();
$students = new Students();
$teachers = new Teachers();
$labour = new Labour();
$prestige = new Prestige();


$data = [
    "general" => [
        "teams" => $general->get_teams(),
        "times" => $general->get_times()
    ],
    "labour" => [
        "jobs" => $labour->get_requirments(),
        "prestige" => $prestige->get_requirments(),
        "standart_skills" => $labour->get_standart_skills()
    ],
    "school_admin" => [
        "students" => $students->get_requirments(),
        "teachers" => $teachers->get_requirments(),
        "buildings" => $buildings->get_requirments()
    ]
];

// returns the json version of the array
echo json_encode($data);

$database->close();

?>
