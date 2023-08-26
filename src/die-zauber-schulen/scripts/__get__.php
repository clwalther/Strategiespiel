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
include "./events.php";

$buildings = new Buildings();
$students  = new Students();
$teachers  = new Teachers();
$labour    = new Labour();
$prestige  = new Prestige();
$fire_of_hogwarts = new Fire_of_Hogwarts();


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
        ],
        "event" => [
            "fire_of_hogwarts" => [
                "enabled" => $fire_of_hogwarts->is_enabled(),
                "share" => $fire_of_hogwarts->get_share(),
                "data" => $fire_of_hogwarts->get_ressources(),
                "weights" => $fire_of_hogwarts->get_weights()
            ]
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
