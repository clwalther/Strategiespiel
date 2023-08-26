<?php

define('BACKUP_FILE_PATH', "/var/www/html/Strategiespiel/conf.d/backups/die-zauber-schulen/", true);
define('DATA_FILE_PATH', '/var/www/html/Strategiespiel/src/assets/data/die-zauber-schulen.json', true);
define('RUN_FILE_PATH', '/var/www/html/Strategiespiel/src/die-zauber-schulen/scripts/__run__.php', true);
define('LOG_FILE_PATH', '/var/www/html/Strategiespiel/conf.d/logs/die-zauber-schulen/', true);
define('PID_FILE_PATH', '/var/www/html/Strategiespiel/conf.d/pid-file.txt', true);

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

    // === GET ===
    // logs
    public function get_logs(): string {
        $budel = explode(";", file_get_contents(PID_FILE_PATH));
        $pid = $budel[0];
        $time = $budel[1];

        $path = sprintf("%s%s", LOG_FILE_PATH, $time);

        return file_get_contents($path);
    }
    // backups
    public function get_backups(): array {
        // returning the all folder enties in backup folder without the two standart dot-folders
        return array_values(array_diff(scandir(BACKUP_FILE_PATH), array('..', '.')));
    }
    // start/pause/reset
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
    // teams
    public function get_teams(): array {
        // reads and returns the team array object
        return $this->database->select("TEAM", ["*"]);
    }
    // general skill
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
    // events
    public function get_events() {

    }

    // === BACKUPS ===
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

    // === START/PAUSE/RESET ===
    public function start(): void {
        // creates a safety backup
        $this->backup("BFR_STRT");

        // kills the previous backend loop
        $this->kill();

        // writes a start time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => 1]);

        // runs the idenpendant clock in the backend
        $time = date("Y-m-d-H:i:s", time());
        $pid = shell_exec(sprintf("(nohup php %s > %s%s 2>&1 & echo $!) 2>&1", RUN_FILE_PATH, LOG_FILE_PATH, $time));
        file_put_contents(PID_FILE_PATH, rtrim($pid).";".$time);
    }

    public function pause(): void {
        // creates a safety backup
        $this->backup("BFR-PAUS");
        // writes a halt/stop time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => 0]);

        // kills the backend loop
        $this->kill();

        // creates a safety backup
        $this->backup("PST_PAUS");
    }

    public function reset(): void {
        // creates a safety backup
        $this->backup("BFR-RST");

        // kills the backend loop
        $this->kill();

        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        // list of all the tables
        $table_names = ["TIME", "TEAM", "SCHOOL_ADMIN", "STUDENTS", "LABOUR", "WORKERS", "FIRE_OF_HOGWARTS"];

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

            // EVENTS
            // FIRE_OF_HOGWARTS
            $this->database->insert("FIRE_OF_HOGWARTS",
            [
                "gorup_id" => $group_id
            ]);
        }
    }
    // required method
    private function kill(): void {
        // kills the backend loop
        $budel = explode(";", file_get_contents(PID_FILE_PATH));
        $pid = $budel[0];
        $time = $budel[1];

        $path = sprintf("%s%s", LOG_FILE_PATH, $time);
        $command = sprintf("kill %s >> %s 2>&1", $pid, $path);

        exec($command, $output, $exit_code);

        // Log the termination status message
        $status = ($exit_code === 0) ? "termination successful\n\n" : "termination failed\n\n";
        file_put_contents($path, $status, FILE_APPEND);
    }

    // === TEAMS ===
    public function change_name(string $value): void {
        // changes the name on a team
        $bundle = explode(";", $value);

        $id   = $bundle[0];
        $name = $bundle[1];

        $this->database->update("TEAM", ["teamname" => $name], ["group_id" => $id]);
    }

    // === EVENTS ===
    public function start_event(string $value): void {

    }

    public function end_event(): void {

    }
}

?>
