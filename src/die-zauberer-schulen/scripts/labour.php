<?php

define('MAX_BASE_POINTS', 5, true);
define('MAX_ADVANCED_POINTS', 2, true);

class Labour
{
    function __construct() {
        global $database;
        global $general;

        $this->database = $database;
        $this->general = $general;
        $this->group_id = $_GET["Team"];
    }

    // === NECESSITIES ===
    public function get_requirements(): array {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // return array carrying necessities
        $send_jobs = array();

        // reads all the jobs from the database with correct group id
        $group = $this->database->select_where("LABOUR", $file["general"]["jobs"], ["group_id" => $this->group_id]);

        // looping through all the workers as worker <job_name;influence>
        foreach($group[0] as $job_name => $influence) {
            // reads all the workers with matching group id and job name
            $workers = $this->database->select_where("WORKERS", ["value", "id"], ["job_name" => $job_name, "group_id" => $this->group_id]);

            // job structre
            $job_struct = ["name" => $job_name, "influence" => $influence, "workers" => array()];

            foreach($workers as $worker) {
                $worker_struct = ["id" => $worker["id"], "skills" => array()];

                $n_skills = count($file["general"]["subjects"]);
                // converts the skill to float
                $skill_repre = floatval($worker["value"]);

                for ($skill_index = 0; $skill_index < $n_skills; $skill_index++) {
                    // aquires all the skill attributes
                    $skill_name = $file["general"]["subjects"][$skill_index];
                    $base_value = $this->general->get_base($skill_repre, $skill_index);
                    $advanced_value = $this->general->get_advanced($skill_repre, $skill_index);

                    // assembles attributes in structre
                    $skill_struct = [
                        "name" => $skill_name,
                        "base" => $base_value,
                        "advanced" => $advanced_value
                    ];

                    // pushes skill into worker structure
                    array_push($worker_struct["skills"], $skill_struct);
                }

                // pushes assembled worker into jobs
                array_push($job_struct["workers"], $worker_struct);
            }

            // pushes assembled jobs into retuning array
            array_push($send_jobs, $job_struct);
        }

        // returns the jobs
        return $send_jobs;
    }

    // === ACTIONS ===
    // TODO: change influence
    public function change_influence(string $value): void {

    }

    public function delete_worker(string $id): void {
        // removes the worker from the database
        $this->database->delete("WORKERS", ["id" => $id]);
    }

    public function add_worker(string $job_name): void {
        // adds a new worker of given type to database
        $this->database->insert("WORKERS", ["group_id" => $this->group_id, "job_name" => $job_name]);
    }

    public function add_base(string $value): void {
        // sets the base value for the last worker by group
        $bundel = explode(";", $value);
        // extracts the information out of bundel
        $skill_index = intval($bundel[0]);
        $new_worker_value = intval($bundel[1]);

        // auires the last worker from workers
        $workers = $this->database->select("WORKERS", ["value", "id"], ["group_id" => $this->group_id]);
        $worker = end($workers);

        $worker_id = $worker["id"];
        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->general->get_base($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->general->add_base($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }

    public function add_advanced(string $value): void {
        // sets the base value for the last worker by group
        $bundel = explode(";", $value);
        // extracts the information out of bundel
        $skill_index = intval($bundel[0]);
        $new_worker_value = intval($bundel[1]);

        // auires the last worker from workers
        $workers = $this->database->select("WORKERS", ["value", "id"], ["group_id" => $this->group_id]);
        $worker = end($workers);

        $worker_id = $worker["id"];

        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->general->get_advanced($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->general->add_advanced($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }

    public function set_base(string $value): void {
        $bundel = explode(";", $value);

        $worker_id = intval($bundel[0]);
        $skill_index = intval($bundel[1]);
        $new_worker_value = intval($bundel[2]);

        // auires the last worker from workers
        $workers = $this->database->select("WORKERS", ["value"], ["group_id" => $this->group_id]);
        $worker = end($workers);

        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->general->get_base($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->general->add_base($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }

    public function set_advanced(string $value): void {
        $bundel = explode(";", $value);

        $worker_id = intval($bundel[0]);
        $skill_index = intval($bundel[1]);
        $new_worker_value = intval($bundel[2]);

        // auires the last worker from workers
        $workers = $this->database->select("WORKERS", ["value"], ["group_id" => $this->group_id]);
        $worker = end($workers);

        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->general->get_advanced($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->general->add_advanced($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }
}

?>
