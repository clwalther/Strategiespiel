<?php

define('MAX_BASE_POINTS', 5, true);
define('MAX_ADVANCED_POINTS', 2, true);
define('N_SKILLS', 7, true);


class Teachers
{
    function __construct() {
        global $database;

        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    public function get_requirments(): array {
        $send_teachers = [];

        $database_response = $this->database->select_where(
            "SCHOOL_ADMIN",
            [
                "Zaubertränke",
                "Zauberkunst",
                "Verteidigung",
                "Geschichte",
                "Geschöpfe",
                "Kräuterkunde",
                "Besenfliegen"
            ],
            ["group_id" => $this->group_id]
        );

        foreach($database_response[0] as $subject => $value) {
            if($value["value"] > 0) {
                $base_skill = ($value["value"] - 1) % pow(MAX_BASE_POINTS, N_SKILLS);
                $advanced_skill = floor(($value["value"] - 1) / pow(MAX_BASE_POINTS, N_SKILLS - 1));

                $teacher_struct = [
                    "name" => $subject,
                    "skills" =>  [
                        ["name" => "Zaubertränke", "base" => $this->get_skill($base_skill, 0, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 0, MAX_ADVANCED_POINTS)],
                        ["name" => "Zauberkunst",  "base" => $this->get_skill($base_skill, 1, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 1, MAX_ADVANCED_POINTS)],
                        ["name" => "Verteidigung", "base" => $this->get_skill($base_skill, 2, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 2, MAX_ADVANCED_POINTS)],
                        ["name" => "Geschichte",   "base" => $this->get_skill($base_skill, 3, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 3, MAX_ADVANCED_POINTS)],
                        ["name" => "Geschöpfe",    "base" => $this->get_skill($base_skill, 4, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 4, MAX_ADVANCED_POINTS)],
                        ["name" => "Kräuterkunde", "base" => $this->get_skill($base_skill, 5, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 5, MAX_ADVANCED_POINTS)],
                        ["name" => "Besenfliegen", "base" => $this->get_skill($base_skill, 6, MAX_BASE_POINTS), "advanced" => $this->get_skill($advanced_skill, 6, MAX_ADVANCED_POINTS)]
                    ],
                ];

                array_push($send_teachers, $teacher_struct);
            }

        }
        return $send_teachers;
    }

    private function get_skill(int $individual_teacher_repr, int $skill_index, int $max_points): int {
        // caluclates the skill value in range 0 - $max_points for given student skill repr
        return floor($individual_teacher_repr / pow($max_points, $skill_index)) % $max_points;
    }

    public function set_advanced(string $bundle): void {
        $bundle = explode(";", $bundle);
        $subject = $bundle[0];
        $skill = intval($bundle[1]);
        $value = intval($bundle[2]);

        $database_response = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        $skill_advanced = floor($database[0][$subject] / pow(MAX_BASE_POINTS, N_SKILLS - 1));
        $skill_value = $this->get_skill($skill_advanced, $skill, MAX_ADVANCED_POINTS);

        $skill_value_delta = ($value - $skill_value) * pow(MAX_ADVANCED_POINTS, $skill) * pow(MAX_BASE_POINTS, N_SKILLS - 1);
        $new_skill_value = $database[0][$subject] + $skill_value_delta;

        $this->database->update("SCHOOL_ADMIN", [$subject => $new_skill_value], ["group_id" => $this->group_id]);
    }

    public function set_base(string $bundle): void {
        $bundle = explode(";", $bundle);
        $subject = $bundle[0];
        $skill = intval($bundle[1]);
        $value = intval($bundle[2]);

        $database_response = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        $skill_value = $this->get_skill($database[0][$subject], $skill, MAX_ADVANCED_POINTS);

        $skill_value_delta = ($value - $skill_value) * pow(MAX_ADVANCED_POINTS, $skill);
        $new_skill_value = $database[0][$subject] + $skill_value_delta;

        $this->database->update("SCHOOL_ADMIN", [$subject => $new_skill_value], ["group_id" => $this->group_id]);
    }
}
?>
