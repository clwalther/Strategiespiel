<?php

class Students
{
    function __construct() {
        global $database;

        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    // === METHODS ===
    private function get_base_repr(int $skill_repre, int $n_skills): int {
        // calculates the base repre from skill repre
        return $skill_repre % pow(MAX_BASE_POINTS, $n_skills);
    }

    private function get_advanced_repr(int $skill_repre, int $n_skills): int {
        // calculates the advanced repre from skill repre
        return floor($skill_repre / pow(MAX_BASE_POINTS, $n_skills));
    }

    private function get_skill(int $individual_student_repr, int $skill_index): int {
        // caluclates the skill value in range 0 - MAX_BASE_POINTS for given student skill repr
        return floor($individual_student_repr / pow(MAX_BASE_POINTS, $skill_index)) % MAX_BASE_POINTS;
    }

    // === NECESSITIES ===
    public function get_requirements(): array {
        // returns the necessities for the frontend
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // return array carrying necessities
        $send_students = array();

        // reads all the students from the database with correct group id
        $students = $this->database->select_where("STUDENTS", ["id", "value"], ["group_id" => $this->group_id]);

        // looping through all the students as student<id;value>
        foreach($students as $student) {
            $n_skills = count($file["general"]["subjects"]);
            // converts the skill to integer
            $skill_repre = intval($student["value"]);
            // aquires the base and advanced values
            $base_repre = $this->get_base_repr($skill_repre, $n_skills);
            $advanced_repre = $this->get_advanced_repr($skill_repre, $n_skills);
            // student structure
            $student_struct = ["id" => $student["id"], "skills" => array()];
            for ($skill_index = 0; $skill_index < $n_skills; $skill_index++) {
                // aquires all the skill attributes
                $skill_name = $file["general"]["subjects"][$skill_index];
                $base_value = $this->get_skill($base_repre, $skill_index);
                $advanced_value = $this->get_skill($advanced_repre, $skill_index);

                // assembles attributes in structre
                $skill_struct = [
                    "name" => $skill_name,
                    "base" => $base_value,
                    "advanced" => $advanced_value
                ];

                // pushes skill into student structure -> skills object
                array_push($student_struct["skills"], $skill_struct);
            }

            // pushes the student structe into return array
            array_push($send_students, $student_struct);
        }

        // returns the students
        return $send_students;
    }

    // === ACTIONS ===
    public function check_out($id) {
        // removes the student from the database
        $this->database->delete("STUDENTS", ["id" => $id]);
    }
}

?>
