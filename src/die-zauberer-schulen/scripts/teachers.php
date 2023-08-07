<?php

class Teachers
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

    private function get_skill(int $teacher_repr, int $skill_index, int $max_points): int {
        // caluclates the skill value in range 0 - $max_points for given teacher skill repr
        return floor($teacher_repr / pow($max_points, $skill_index)) % $max_points;
    }

    // === NECESSITIES ===
    public function get_requirements(): array {
        // returns the necessities for the frontend
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);
        // return array carrying necessities
        $send_teachers = array();

        // aquires correct group from group id with all teachers
        $group = $this->database->select_where("SCHOOL_ADMIN", $file["general"]["subjects"], ["group_id" => $this->group_id]);

        // loops through all teachers
        foreach($group[0] as $teacher => $skill_repre) {
            // converts the skill to integer and subtracts one to check for enabled
            $skill_repre = intval($skill_repre) - 1;

            // if the skill_repre is greater than negative one -> teacher slot enabled
            if($skill_repre > -1) {
                $n_skills = count($file["general"]["subjects"]);
                // aquires the base and advanced values
                $base_repre = $this->get_base_repr($skill_repre, $n_skills);
                $advanced_repre = $this->get_advanced_repr($skill_repre, $n_skills);
                // teacher structure
                $teacher_struct = ["name" => $teacher, "skills" => array()];

                for ($skill_index = 0; $skill_index < $n_skills; $skill_index++) {
                    // aquires all the skill attributes
                    $skill_name = $file["general"]["subjects"][$skill_index];
                    $base_value = $this->get_skill($base_repre, $skill_index, MAX_BASE_POINTS);
                    $advanced_value = $this->get_skill($advanced_repre, $skill_index, MAX_ADVANCED_POINTS);

                    // assembles attributes in structre
                    $skill_struct = [
                        "name" => $skill_name,
                        "base" => $base_value,
                        "advanced" => $advanced_value
                    ];

                    // pushes skill into teacher structure -> skills object
                    array_push($teacher_struct["skills"], $skill_struct);
                }

                // pushes the teacher structe into return array
                array_push($send_teachers, $teacher_struct);
            }

        }

        // returns the teachers
        return $send_teachers;
    }

    // === ACTIONS ===
    public function set_advanced(string $bundle): void {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $bundle = explode(";", $bundle);
        // bundel => subject;int(skill);int(advanced)
        $subject = $bundle[0];
        $skill_index = intval($bundle[1]);
        $advanced_value = intval($bundle[2]);

        $n_skills = count($file["general"]["subjects"]);

        // aquires correct group from group id with specific teacher
        $teacher = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        // aquires all the skill attributes
        $advanced_repre = $this->get_advanced_repr(intval($teacher[0][$subject]) - 1, $n_skills);

        // gets the old skill value
        $old_value = $this->get_skill($advanced_repre, $skill_index, MAX_ADVANCED_POINTS);

        // calculates the delta and formats it
        // further adding it to the repre
        $advanced_repre_delta = ($advanced_value - $old_value) * pow(MAX_ADVANCED_POINTS, $skill_index);
        $new_teacher_repr = $teacher[0][$subject] + $advanced_repre_delta * pow(MAX_BASE_POINTS, $n_skills);

        // updating the database
        $this->database->update("SCHOOL_ADMIN", [$subject => $new_teacher_repr], ["group_id" => $this->group_id]);
    }

    public function set_base(string $bundle): void {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $bundle = explode(";", $bundle);
        // bundel => subject;int(skill);int(base)
        $subject = $bundle[0];
        $skill_index = intval($bundle[1]);
        $base_value = intval($bundle[2]);

        $n_skills = count($file["general"]["subjects"]);

        // aquires correct group from group id with specific teacher
        $teacher = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        // aquires all the skill attributes
        $base_repre = $this->get_base_repr(intval($teacher[0][$subject]) - 1, $n_skills);

        // gets the old skill value
        $old_value = $this->get_skill($base_repre, $skill_index, MAX_BASE_POINTS);

        // calculates the delta and formats it
        // further adding it to the repre
        $base_repre_delta = ($base_value - $old_value) * pow(MAX_BASE_POINTS, $skill_index);
        $new_teacher_repr = $teacher[0][$subject] + $base_repre_delta;

        // updating the database
        $this->database->update("SCHOOL_ADMIN", [$subject => $new_teacher_repr], ["group_id" => $this->group_id]);
    }
}
?>
