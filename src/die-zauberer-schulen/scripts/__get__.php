<?php


include "../../scripts/global.php";
include "./general.php";
include "./buildings.php";
include "./students.php";

$general = new General();
$buildings = new Buildings();
$students = new Students();


$array = [
    "general" => [
        "teams" => $general->get_teams(),
        "times" => $general->get_times()
    ],
    "labour" => [

    ],
    "school_admin" => [
        "students" => $students->get_requirments(),
        // "teachers" => "none",
        "buildings" => $buildings->get_requirments()
    ]
];

echo json_encode($array);

?>
