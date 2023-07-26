<?php

define('MAX_STUDENT_POINTS', 5, true);
define('N_SKILLS', 7, true);

class Students
{
    function __construct() {
        global $database;

        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    public function get_requirments(): array {
        $send_students = [];

        $database_response = $this->database->select_where("STUDENTS", ["id", "value"], ["group_id" => $this->group_id]);

        foreach($database_response as $student) {
            $students_struct = [
                "id" => $student["id"],
                "skills" => [
                    ["name" => "Zaubertränke", "value" => $this->get_skill($student["value"], 0)],
                    ["name" => "Zauberkunst",  "value" => $this->get_skill($student["value"], 1)],
                    ["name" => "Verteidigung", "value" => $this->get_skill($student["value"], 2)],
                    ["name" => "Geschichte",   "value" => $this->get_skill($student["value"], 3)],
                    ["name" => "Geschöpfe",    "value" => $this->get_skill($student["value"], 4)],
                    ["name" => "Kräuterkunde", "value" => $this->get_skill($student["value"], 5)],
                    ["name" => "Besenfliegen", "value" => $this->get_skill($student["value"], 6)]
                ]
            ];

            array_push($send_students, $students_struct);
        }

        return $send_students;
    }

    private function get_skill(int $individual_student_repr, int $skill_index): int {
        // caluclates the skill value in range 0 - MAX_STUDNET_POINTS for given student skill repr
        return ($individual_student_repr / pow(MAX_STUDENT_POINTS, $skill_index)) % MAX_STUDENT_POINTS;
    }

    public function check_out_student(int $id) {
        $this->database->delete("STUDENTS", ["id" => $id]);
    }
}

?>
