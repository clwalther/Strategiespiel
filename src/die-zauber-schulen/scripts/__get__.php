<?php

include "../../scripts/global.php";
include "./_general_.php";
include "./_utils_.php";

$general = new General();
$utils = new Utils();

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


if ($_GET["Team"] != "undefined") {
    // team specific response
    $data = [
        "general" => [
            "teams"     => $general->get_teams(),
            "times"     => $general->get_times(),
            "skills"    => $general->get_skills(),
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
} else {
    // general for teams
    $data = [
        "general" => [
            "teams"   => $general->get_teams(),
            "times"   => $general->get_times(),
            "backups" => $general->get_backups(),
            "logs"    => $general->get_logs()
            // "events"  => $general->get_events()
        ],

        "buildings" => $buildings->get_teams_progress(),
        "influence" => $labour->get_jobs_influence(),
        "prestige"  => $prestige->get_teams_prestige()
    ];
}

// returns the json version of the array
echo json_encode($data);

$database->close();

?>
