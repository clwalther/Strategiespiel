<?php

include "../../scripts/global.php";
include "./general.php";

$general   = new General();

include "./buildings.php";
include "./students.php";
include "./teachers.php";
include "./labour.php";
include "./prestige.php";


$buildings = new Buildings();
$students  = new Students();
$teachers  = new Teachers();
$labour    = new Labour();
$prestige  = new Prestige();

$data = [
    "general" => [
        "teams"     => $general->get_teams(),
        "times"     => $general->get_times(),
        "skills"    => $general->get_skills(),
        "backups"   => $general->get_backups()
    ],
    "labour" => [
        "jobs"      => $labour->get_requirements(),
        "prestige"  => $prestige->get_requirements(),
    ],
    "school_admin" => [
        "students"  => $students->get_requirements(),
        "teachers"  => $teachers->get_requirements(),
        "buildings" => $buildings->get_requirements()
    ]
];

// returns the json version of the array
echo json_encode($data);

$database->close();

?>
