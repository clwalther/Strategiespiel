<?php

class Utils
{
    function __construct() {
        global $database;

        $this->database = $database;
    }

    // === SKILLS ===
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

    // === BUILDINGS ===
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

    public function search_for_perk(int $group_id, string $perk_name, array $building_knot, array &$values) {
        foreach($building_knot as $building_name => $building) {
            // check for whetver the building is active or not
            if($this->get_building_status($this->get_building_id($building_name), $group_id)) {
                // extract the perks from the building
                foreach ($building["perks"] as $name => $value) {
                    if($name === $perk_name) {
                        // pushes value into array
                        array_push($values, $value);
                    }
                }
            }

            // calls the next building knot
            if($building["children"] != "none") {
                $this->search_for_perk($group_id, $perk_name, $building["children"], $values);
            }
        }
    }

    // === INFLUENCE ===
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
