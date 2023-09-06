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

include "./GraduatesGenerator.php";

$buildings = new Buildings();
$students  = new Students();
$teachers  = new Teachers();
$labour    = new Labour();
$prestige  = new Prestige();
$fire_of_hogwarts = new Fire_of_Hogwarts();

$graduates_generator = new GraduatesGenerator();


if ($_GET["Team"] != "undefined") {
    // team specific response
    $data = [
        "general" => [
            "teams"     => $general->get_teams(),
            "times"     => $general->get_times(),
            "skills"    => $general->get_skills(),
            "time_now"  => $general->get_time_now()
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
                "share"   => $fire_of_hogwarts->get_share(),
                "data"    => $fire_of_hogwarts->get_ressources()
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
            "logs"    => $general->get_logs(),
            "time_now"  => $general->get_time_now()
        ],

        "buildings" => $buildings->get_teams_progress(),
        "influence" => $labour->get_jobs_influence(),
        "prestige"  => $prestige->get_teams_prestige(),
        "displacement" => $graduates_generator->get_gaussian_displacement(),

        "event" => [
            "fire_of_hogwarts" => [
                "enabled" => $fire_of_hogwarts->is_enabled(),
                "points"  => $fire_of_hogwarts->get_points(),
                "time"    => $fire_of_hogwarts->get_time_ratio()
            ]
        ]
    ];
}

// returns the json version of the array
echo json_encode($data);

$database->close();

?>
