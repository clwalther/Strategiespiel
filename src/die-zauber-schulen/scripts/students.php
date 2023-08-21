<?php

class Students
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
            $skill_repre = floatval($student["value"]);

            // student structure
            $student_struct = ["id" => $student["id"], "skills" => array()];
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
    public function check_out(string $id) {
        // removes the student from the database
        $this->database->delete("STUDENTS", ["id" => $id]);
    }
}

?>
