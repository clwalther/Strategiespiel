<?php

class Teachers
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

    // === METHODS ===
    private function is_loaded(string $teacher): bool {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $root = $file["buildings"];

        if(in_array($teacher, $file["general"]["default_teachers"])) {
            return true;
        }
        // loops through all branches and returns the id if found
        $is_included = false;
        $this->search_knot_for_teacher($teacher, $root, $is_included);
        return $is_included;
    }

    private function search_knot_for_teacher(string $teacher, array $knot, int &$is_included) {
        // loops through all buildings in a knot
        foreach($knot as $building_name => $building) {
            // if correct element found return counter
            if($teacher == $building["perks"]["Lehrer"]) {
                $building_id = $this->utils->get_building_id($building_name);
                $is_included = $this->utils->get_building_status($building_id, $this->group_id);
            }

            // aquire the f(x+1) generation
            $filial_knot = $building["children"];

            // checks if knot is authentic
            if($filial_knot != "none") {
                // searches the next branch forming from the knot
                $this->search_knot_for_teacher($teacher, $filial_knot, $is_included);
            }
        }
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
            // converts the skill to float and subtracts one to check for enabled
            $skill_repre = floatval($skill_repre);

            // if the skill_repre is greater than negative one -> teacher slot enabled
            if($this->is_loaded($teacher)) {
                $n_skills = count($file["general"]["subjects"]);
                // gets the teacher displacment
                $add_value = floatval($this->database->select_where("SCHOOL_ADMIN", [$teacher."_displacement"], ["group_id" => $this->group_id])[0][$teacher."_displacement"]);
                // teacher structure
                $teacher_struct = ["name" => $teacher, "skills" => array(), "add" => $add_value];

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
    public function set_base(string $bundle): void {
        $bundle = explode(";", $bundle);
        // bundel => subject;int(skill);int(base)
        $subject = $bundle[0];
        $skill_index = intval($bundle[1]);
        $new_base_value = intval($bundle[2]);


        // aquires correct group from group id with specific teacher
        $teacher = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        // aquires all the skill attributes
        $old_base_value = $this->utils->get_base(floatval($teacher[0][$subject]), $skill_index);
        $base_delta = ($new_base_value - $old_base_value);
        echo $base_delta;

        $base = $this->utils->add_base(floatval($teacher[0][$subject]), $skill_index, $base_delta);

        // updating the database
        $this->database->update("SCHOOL_ADMIN", [$subject => $base], ["group_id" => $this->group_id]);
    }

    public function set_advanced(string $bundle): void {
        $bundle = explode(";", $bundle);
        // bundel => subject;int(skill);int(advanced)
        $subject = $bundle[0];
        $skill_index = intval($bundle[1]);
        $new_advanced_value = intval($bundle[2]);

        // aquires correct group from group id with specific teacher
        $teacher = $this->database->select_where("SCHOOL_ADMIN", [$subject], ["group_id" => $this->group_id]);
        // aquires all the skill attributes
        $old_advanced_value = $this->utils->get_advanced(floatval($teacher[0][$subject]), $skill_index);
        $advanced_delta = ($new_advanced_value - $old_advanced_value);

        $advanced = $this->utils->add_advanced(floatval($teacher[0][$subject]), $skill_index, $advanced_delta);

        // updating the database
        $this->database->update("SCHOOL_ADMIN", [$subject => $advanced], ["group_id" => $this->group_id]);
    }

    public function set_displacement(string $bundle): void {
        $bundle = explode(";", $bundle);
        // bundel => ubject;int(displacement)
        $subject = $bundle[0];
        $displacment = floatval($bundle[1]);

        // updating the database
        $this->database->update("SCHOOL_ADMIN", [$subject."_displacement" => $displacment], ["group_id" => $this->group_id]);
    }
}
?>
