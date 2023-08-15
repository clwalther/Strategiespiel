<?php

include "../../scripts/global.php";
include "./general.php";

$general = new General();

include "./GraduatesGenerator.php";
include "./PrestigeDistributor.php";

$grads_generation = new GraduatesGenerator();
$prestige_dist = new PrestigeDistributer();

class Clock
{
    function __construct() {
        global $database;
        global $general;

        $this->database = $database;
        $this->general = $general;

        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $this->file = json_decode($file, true);

        // init array
        $this->time_array = array();
    }

    public function start(): void {
        echo "\n=== STARTING ===\nstarting loop...\n\n";

        $this->step($this->time_array);
    }

    public function create_element(string $key, float $start_time, string $aqurie_time_function, string $event_function): void {
        $this->time_array[$key] = [
            "time" => $start_time,
            "aquire" => $aqurie_time_function,
            "function" => $event_function
        ];
    }

    private function step(array $time_array): void {
        // checks for if game is still running
        if($this->is_running()) {
            // formatting the output
            echo "UPDATING:";

            // loops thorugh all teams given by times
            foreach($time_array as $key => &$object) {
                // if time is zero meaning the function was called by item
                if($object["time"] == 0) {
                    // calling the event function
                    $object["function"]($key);

                    // gets the new time for expired group
                    $object["time"] = $object["aquire"]($key);

                    // prints the team that has been updated
                    echo sprintf(" %s;", $key);
                }
            }

            // get minimum time
            $min_time = $this->get_min_time($time_array);

            // formatting the output
            echo sprintf("\n\nNEXT STEP IN: %s min...\n\n", $min_time);

            // sleeps the given minimum time [seconds] => [minutes]
            sleep($min_time * 60);

            // manipulating for next step
            $this->update_times($min_time, $time_array);
            $this->step($time_array);
        } else {
            // exit output
            echo "\n\n=== ABORTING ===\naborting loop...\n\n";
        }

    }

    private function is_running(): bool {
        // aquires the time logs from database
        $time_logs = $this->database->select("TIME", ["type"]);
        // take the last log and checks type
        return intval(end($time_logs)["type"]) == 1;
    }

    private function update_times(float $delta, array &$time_array): void {
        // loping through all items in times
        foreach($time_array as $key => &$value) {
            // subtracting the timedelta form  each time
            $value["time"] -= $delta;
        }
    }

    private function get_min_time(array $time_array): float {
        // returns the min time value of the main array
        $times = array();

        // extracts the time value out of the main array
        foreach ($time_array as $key => $value) {
            // pushes the value in times
            array_push($times, $value["time"]);
        }

        // returns the min value of times
        return min($times);
    }
}

function setup_clock() {
    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    // initilizing the clock
    $clock = new Clock();

    // setting up backup
    // TODO: fix linux permission
    // $clock->create_element("backup", 0, 'get_backup_interval', 'backup_event');

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
    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    return 10;
}

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
    global $grads_generation;

    // aquires the file contents
    $file = file_get_contents(DATA_FILE_PATH);
    $file = json_decode($file, true);

    // extracts infromation
    $bundel = explode("=", $key);
    $group_id = intval($bundel[1]);

    $student_issues = $file["general"]["inital_graduates_issue"];

    // generates inital_graduates_issue graduates
    for($i = 0; $i < $student_issues; $i++) {
        $grads_generation->generate_graduate($group_id);
    }
}

function search_for_perk(array $building_knot) {
    foreach($building_knot as $building_name => $building) {


        $return = search_for_perk();

        if($return) { return true; }
    }
}

// setting up and running the clock
setup_clock();

// closing the connection to the database
$database->close();

?>
