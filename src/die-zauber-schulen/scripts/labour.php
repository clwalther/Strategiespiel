<?php

class Labour
{
    function __construct() {
        global $database;
        global $general;
        global $utils;

        $this->database = $database;
        $this->general = $general;
        $this->utils = $utils;
        $this->group_id = $_GET["Team"];
    }

    // === NECESSITIES ===
    public function get_requirements(): array {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // return array carrying necessities
        $send_jobs = array();

        // looping through all the workers as worker <job_name;influence>
        foreach($file["general"]["jobs"] as $job_name) {
            // reads all the workers with matching group id and job name
            $influence = $this->utils->get_influence($job_name)[$this->group_id];
            $workers = $this->database->select_where("WORKERS", ["value", "id"], ["job_name" => $job_name, "group_id" => $this->group_id]);

            // job structre
            $job_struct = ["name" => $job_name, "influence" => $influence, "workers" => array(), "requirements" => $file["general"]["job_requirements"][$job_name]];

            foreach($workers as $worker) {
                $worker_struct = ["id" => $worker["id"], "skills" => array()];

                $n_skills = count($file["general"]["subjects"]);
                // converts the skill to float
                $skill_repre = floatval($worker["value"]);

                for ($skill_index = 0; $skill_index < $n_skills; $skill_index++) {
                    // aquires all the skill attributes
                    $skill_name = $file["general"]["subjects"][$skill_index];
                    $base_value = $this->utils->get_base($skill_repre, $skill_index);
                    $advanced_value = $this->utils->get_advanced($skill_repre, $skill_index);

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

    public function get_jobs_influence(): array {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $jobs = array();

        foreach($file["general"]["jobs"] as $job_name) {
            $requirements = array();

            foreach($file["general"]["job_requirements"][$job_name] as $skill_index) {
                array_push($requirements, $file["general"]["subjects"][$skill_index]);
            }

            $jobs[$job_name] = [
                "influence" => $this->utils->get_influence($job_name),
                "requirements" => $requirements
            ];
        }

        return $jobs;
    }

    // === ACTIONS ===
    public function change_influence(string $value): void {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // bundel: <job_name;influence>
        $bundel = explode(";", $value);
        // extracts the information out of bundel
        $job_name = $bundel[0];
        $influence = floatval($bundel[1]);

        // combined points total
        $combined_points = 0;

        // loops through all teams
        foreach($file["general"]["teams"] as $group_id => $group_name) {
            // gets the total points making up the influence in a job
            // 1.: worker points
            // 2.: (+) extra added points
            $group_points = $this->utils->get_points_workers(intval($group_id), $job_name);
            $group_points += $this->utils->get_points_extra(intval($group_id), $job_name);

            // adds the group specific points to one value
            $combined_points += $group_points;
        }

        $group_worker_points = $this->utils->get_points_workers(intval($this->group_id), $job_name);
        $group_extra_points = $this->utils->get_points_extra(intval($this->group_id), $job_name);

        // p_group_extra = (I * (SUM:[p] - p_group) / (1 - I)) - p_group_worker
        $add_points = ($influence * ($combined_points - ($group_worker_points + $group_extra_points))
            / (1 - $influence)) - $group_worker_points;

        $this->database->update("LABOUR", [$job_name => $add_points], ["group_id" => $this->group_id]);
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
        $old_worker_value = $this->utils->get_base($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->utils->add_base($worker_repre, $skill_index, $worker_delta);

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
        $old_worker_value = $this->utils->get_advanced($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->utils->add_advanced($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }

    public function set_base(string $value): void {
        $bundel = explode(";", $value);

        $worker_id = intval($bundel[0]);
        $skill_index = intval($bundel[1]);
        $new_worker_value = intval($bundel[2]);

        // auires the last worker from workers
        $workers = $this->database->select_where("WORKERS", ["value"], ["group_id" => $this->group_id, "id" => $worker_id]);
        $worker = $workers[0];

        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->utils->get_base($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->utils->add_base($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }

    public function set_advanced(string $value): void {
        $bundel = explode(";", $value);

        $worker_id = intval($bundel[0]);
        $skill_index = intval($bundel[1]);
        $new_worker_value = intval($bundel[2]);

        // auires the last worker from workers
        $workers = $this->database->select_where("WORKERS", ["value"], ["group_id" => $this->group_id, "id" => $worker_id]);
        $worker = $workers[0];

        $worker_repre = floatval($worker["value"]);
        $old_worker_value = $this->utils->get_advanced($worker_repre, $skill_index);

        // adds the delta to worker representation
        $worker_delta = $new_worker_value - $old_worker_value;
        $worker = $this->utils->add_advanced($worker_repre, $skill_index, $worker_delta);

        // updates the database
        $this->database->update("WORKERS", ["value" => $worker], ["id" => $worker_id]);
    }
}

?>
