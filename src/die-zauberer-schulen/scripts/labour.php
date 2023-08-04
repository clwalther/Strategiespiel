<?php

define('MAX_BASE_POINTS', 5, true);
define('MAX_ADVANCED_POINTS', 2, true);

class Labour
{
    function __construct() {
        global $database;

        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    public function get_requirments(): array {
        $send_jobs = [];

        $database_response = $this->database->select_where(
            "LABOUR",
            [
                "Medimagier",
                "Auror",
                "Ministeriumsbeamter",
                "Drachenwärter",
                "Magiezoologe",
                "Zauberstabschreinermeister",
                "Quidditchprofi"
            ],
            ["group_id" => $this->group_id]
        );

        foreach($database_response[0] as $job_name => $influence) {
            $workers = $this->database->select_where("WORKERS", ["value"], ["job_name" => $job_name, "group_id" => $this->group_id]);

            $job_struct = [
                "name" => $job_name,
                "influence" => $influence,
                "workers" => []
            ];

            foreach($workers as $worker) {
                array_push($job_struct["workers"], [
                    ["name" => "Zaubertränke", "base" => $this->get_skill($worker["value"], 0, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 0, MAX_ADVANCED_POINTS)],
                    ["name" => "Zauberkunst",  "base" => $this->get_skill($worker["value"], 1, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 1, MAX_ADVANCED_POINTS)],
                    ["name" => "Verteidigung", "base" => $this->get_skill($worker["value"], 2, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 2, MAX_ADVANCED_POINTS)],
                    ["name" => "Geschichte",   "base" => $this->get_skill($worker["value"], 3, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 3, MAX_ADVANCED_POINTS)],
                    ["name" => "Geschöpfe",    "base" => $this->get_skill($worker["value"], 4, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 4, MAX_ADVANCED_POINTS)],
                    ["name" => "Kräuterkunde", "base" => $this->get_skill($worker["value"], 5, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 5, MAX_ADVANCED_POINTS)],
                    ["name" => "Besenfliegen", "base" => $this->get_skill($worker["value"], 6, MAX_BASE_POINTS), "advanced" => $this->get_skill($worker["value"], 6, MAX_ADVANCED_POINTS)]
                ]);
            }

            array_push($send_jobs, $job_struct);
        }

        return $send_jobs;
    }

    public function get_standart_skills(): array {
        return [
            ["name" => "Zaubertränke", "base" => 0, "advanced" => 0],
            ["name" => "Zauberkunst",  "base" => 0, "advanced" => 0],
            ["name" => "Verteidigung", "base" => 0, "advanced" => 0],
            ["name" => "Geschichte",   "base" => 0, "advanced" => 0],
            ["name" => "Geschöpfe",    "base" => 0, "advanced" => 0],
            ["name" => "Kräuterkunde", "base" => 0, "advanced" => 0],
            ["name" => "Besenfliegen", "base" => 0, "advanced" => 0]
        ];
    }

    private function get_skill(int $teacher_repr, int $skill_index, int $max_points): int {
        // caluclates the skill value in range 0 - $max_points for given student skill repr
        return floor($teacher_repr / pow($max_points, $skill_index)) % $max_points;
    }
}

?>
