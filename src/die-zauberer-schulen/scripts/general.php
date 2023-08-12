<?php

define('BACKUP_FILE_PATH', "/var/www/html/Strategiespiel/conf.d/backups/die-zauberer-schulen/", true);
define('DATA_FILE_PATH', '/var/www/html/Strategiespiel/src/assets/data/die-zauberer-schulen.json', true);

define('BASE_SKILL_STATES', 8, true);
define('ADVANCED_SKILL_STATES', 4, true);

define('BUILDING_STATES', 2, true);

define('WORKER_PARA_ALPHA', 3, true);
define('WORKER_PARA_BETA', 2, true);
define('WORKER_PARA_GAMMA', 1, true);

class General
{
    function __construct() {
        global $database;

        $this->database = $database;
    }

    // UTILS
    public function backup(string $name = "STNDRT"): void {
        // list of all the tables
        $table_names = ["TIME", "TEAM", "SCHOOL_ADMIN", "STUDENTS", "LABOUR", "WORKERS"];

        // path to backup folder with timestamp
        $time_struct = date("Y-m-d H:i:s", time());
        $folder_path = sprintf(BACKUP_FILE_PATH."%s %s", $time_struct, $name);

        // creates the backup folder with the timestamp
        mkdir($folder_path);

        // loops through all the tables
        foreach ($table_names as $table_name) {
            // path to file
            $file_path = sprintf("%s/%s.csv", $folder_path, $table_name);
            // backups the table
            $this->database->backup_table($table_name, $file_path);
        }
    }

    public function load_backup(string $folder_path): void {
        // creates a safety backup
        $this->backup("BFR-LDNG");

        // list of all the tables
        $table_names = ["TIME", "TEAM", "SCHOOL_ADMIN", "STUDENTS", "LABOUR", "WORKERS"];

        // loops through all the tables
        foreach ($table_names as $table_name) {
            // creates the file pointer
            $file_path = sprintf("%s/%s.csv", $folder_path, $table_name);
            // loads the table
            $this->database->load_table_backup($table_name, $file_path);
        }
    }

    public function get_backups(): array {
        // returning the all folder enties in backup folder without the two standart dot-folders
        return array_values(array_diff(scandir(BACKUP_FILE_PATH), array('..', '.')));
    }

    public function reset(): void {
        // creates a safety backup
        $this->backup("BFR-RST");

        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        // list of all the tables
        $table_names = ["TIME", "TEAM", "SCHOOL_ADMIN", "STUDENTS", "LABOUR", "WORKERS"];

        // deletes all entries from all tables
        foreach($table_names as $table_name) {
            $this->database->delete_alL($table_name);
        }

        foreach(array_keys($file["general"]["teams"]) as $group_id) {
            // SCHOOL ADMINISTRATION
            $this->database->insert("SCHOOL_ADMIN",
            [
                "group_id" => $group_id
            ]);

            // LABOUR
            $this->database->insert("LABOUR",
            [
                "group_id" => $group_id
            ]);

            // TEAM
            $this->database->insert("TEAM",
            [
                "group_id" => $group_id,
                "teamname" => $file["general"]["teams"][$group_id]
            ]);
        }
    }

    // TIME SPECIFIC
    public function start(): void {
        // creates a safety backup
        $this->backup("BFR_STRT");
        // writes a start time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => 1]);
        // creates a safety backup
        $this->backup("PST_STRT");
    }

    public function pause(): void {
        // creates a safety backup
        $this->backup("BFR-PAUS");
        // writes a halt/stop time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => 0]);
        // creates a safety backup
        $this->backup("PST_PAUS");
    }

    public function get_times(): array {
        // reqads all time logs from database
        $time_logs = $this->database->select("TIME", ["time", "type"]);

        // 1.: gets the last type from database to determine wheter halted or not
        // 2.: writes all of the time logs into time logs send object
        $send_times = [
            "is_running" => end($time_logs)["type"],
            "times" => $time_logs
        ];

        // returns the time array object
        return $send_times;
    }

    // TEAM SPECIFIC
    public function get_teams(): array {
        // reads and returns the team array object
        return $this->database->select("TEAM", ["*"]);
    }

    public function change_name($value): void {
        // changes the name on a team
        $bundle = explode(";", $value);

        $id   = $bundle[0];
        $name = $bundle[1];

        $this->database->update("TEAM", ["teamname" => $name], ["group_id" => $id]);
    }

    // SKILLS
    public function get_skills(): array {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // returning array
        $skills_set = array();

        // loops through all subjects
        foreach($file["general"]["subjects"] as $subject_names) {
            $skill_struct = ["name" => $subject_names, "base" => 0, "advanced" => 0];

            array_push($skills_set, $skill_struct);
        }

        return $skills_set;
    }

    // EXTRACT SKILLS
    public function get_base(float $repre, int $skill_index): int {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        $n_skills = count($file["general"]["subjects"]);

        // get base representation
        $base_repre = fmod($repre, pow(BASE_SKILL_STATES, $n_skills));

        // returns the skill as int
        return intval(fmod(floor($base_repre / pow(BASE_SKILL_STATES, $skill_index)), BASE_SKILL_STATES));
    }

    public function get_advanced(float $repre, int $skill_index): int {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        $n_skills = count($file["general"]["subjects"]);

        // get advanced representation
        $advanced_repre = floor(floatval($repre) / pow(BASE_SKILL_STATES, $n_skills));

        // returns the skill as int
        return intval(fmod(floor($advanced_repre / pow(ADVANCED_SKILL_STATES, $skill_index)), ADVANCED_SKILL_STATES));
    }

    public function add_base(float $repre, int $skill_index, int $delta): float {
        return $repre + $delta * pow(BASE_SKILL_STATES, $skill_index);
    }

    public function add_advanced(float $repre, int $skill_index, int $delta): float {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        $n_skills = count($file["general"]["subjects"]);

        return $repre + $delta * pow(ADVANCED_SKILL_STATES, $skill_index) * pow(BASE_SKILL_STATES, $n_skills);
    }

    // BUILDINGS
    public function get_building_id(string $name): ?int {
        // returns the id of a building
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $root = $file["buildings"];

        // loops through all branches and returns the id if found
        $counter = 0;
        $this->search_knot_for_name($name, $root, $counter);
        return $counter;
    }

    private function search_knot_for_name(string $name, array $knot, int &$counter) {
        // loops through all buildings in a knot
        foreach($knot as $building_name => $building) {
            // if correct element found return counter
            if($name === $building_name) { return $counter; }

            // aquire the f(x+1) generation
            $filial_knot = $building["children"];

            $counter++;
            // checks if knot is authentic
            if($filial_knot != "none") {
                // searches the next branch forming from the knot
                $queries = $this->search_knot_for_name($name, $filial_knot, $counter);

                // if the result is not null return the counter
                if($queries != NULL) { return $queries; }
            }
        }
    }

    public function get_building_status(int $id, $group_id): bool {
        // aquiers the status of certain building id
        // in the case that "none" is parent node it needs to return true
        if($id < 0) { return false; }
        // reads the building_repr from the corresponding group
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $group_id]);

        // true => active; false => deactivated;
        return ($building_statuses[0]["buildings"] & pow(BUILDING_STATES, $id)) == pow(BUILDING_STATES, $id);
    }

    // INFLUENCE
    public function get_influence(string $job_name) {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $combined_points = 0;
        $group_specific_points = array();
        $group_specific_influence = array();

        foreach($file["general"]["teams"] as $group_id => $group_name) {
            $group_points = $this->get_points_workers(intval($group_id), $job_name);
            $group_points += $this->get_points_extra(intval($group_id), $job_name);

            $combined_points += $group_points;

            $group_specific_points[$group_id] = $group_points;
        }

        foreach($group_specific_points as $group_id => $group_points) {
            if($combined_points != 0) {
                $group_influence = $group_points / $combined_points;
            } else {
                $group_influence = $group_points;
            }

            $group_specific_influence[$group_id] = $group_influence;
        }

        return $group_specific_influence;
    }

    public function get_points_workers(int $group_id, string $job_name): float {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $points_workers = 0;
        $workers = $this->database->select_where("WORKERS", ["value"], ["job_name" => $job_name, "group_id" => $group_id]);

        foreach($workers as $worker) {
            $job_requirements = $file["general"]["job_requirements"][$job_name];

            $value_alpha = $this->get_base(floatval($worker["value"]), $job_requirements[0]);
            $value_beta = $this->get_base(floatval($worker["value"]), $job_requirements[1]);
            $value_gamma = $this->get_base(floatval($worker["value"]), $job_requirements[2]);

            $points_workers = WORKER_PARA_ALPHA * $value_alpha
                            + WORKER_PARA_BETA * $value_beta
                            + WORKER_PARA_GAMMA * $value_gamma;
        }

        return $points_workers;
    }

    public function get_points_extra(int $group_id, string $job_name) {
        $points = $this->database->select_where("LABOUR", [$job_name], ["group_id" => $group_id]);
        return $points[0][$job_name];
    }
}

?>
