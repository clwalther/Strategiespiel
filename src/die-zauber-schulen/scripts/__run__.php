<?php

include "../../scripts/global.php";
include "./_general_.php";
include "./_utils_.php";

$general = new General();
$utils = new Utils();

include "./Clock.php";
include "./GraduatesGenerator.php";
include "./PrestigeDistributor.php";

$grads_generation = new GraduatesGenerator();
$prestige_dist = new PrestigeDistributer();

// setting up the clock
function setup_clock() {
    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    // initilizing the clock
    $clock = new Clock();

    // setting up backup
    $clock->create_element("backup", 0, 'get_backup_interval', 'backup_event');

    // setting up prestige clock
    $clock->create_element("prestige", 0, 'get_pretige_interval', 'prestige_event');

    // setting up student clock
    foreach($file["general"]["teams"] as $group_id => $teamname) {
        // constructs the key
        $key = sprintf("student=%s", $group_id);
        // creates the individual element
        $clock->create_element($key, 0, 'get_student_interval', 'student_event');
    }

    // starting the clock
    $clock->start();
}

// callback functions for Object::Clock
// callback for intervals -> floats
function get_backup_interval(string $key): float {
    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    return floatval($file["general"]["backup_interval"]);
}

function get_pretige_interval(string $key): float {
    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    return floatval($file["general"]["presige_interval"]);
}

function get_student_interval(string $key): float {
    global $utils;

    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    // extracts infromation
    $bundel = explode("=", $key);
    $group_id = intval($bundel[1]);

    $intervals = [$file["general"]["inital_graduates_interval"]];
    $utils->search_for_perk($group_id, "Absolventenzeit", $file["buildings"], $intervals);

    $student_update_interval = min($intervals);

    return $student_update_interval;
}

// callbck for events -> voids
function backup_event(string $key): void {
    global $general;

    // calls for backup
    $general->backup();
}

function prestige_event(string $key): void {
    global $prestige_dist;

    // distributes prestige
    $prestige_dist->distributePrestigeOfAllJobs();
}

function student_event(string $key): void {
    global $utils;
    global $grads_generation;

    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    // extracts infromation
    $bundel = explode("=", $key);
    $group_id = intval($bundel[1]);

    $issues = [$file["general"]["inital_graduates_issue"]];
    $utils->search_for_perk($group_id, "Absolventenanzahl", $file["buildings"], $issues);

    $student_issues = max($issues);

    // generates inital_graduates_issue graduates
    for($i = 0; $i < $student_issues; $i++) {
        $grads_generation->generate_graduate($group_id);
    }
}

// setting up and running the clock
setup_clock();

// closing the connection to the database
$database->close();

?>
