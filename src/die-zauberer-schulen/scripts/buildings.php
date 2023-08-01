<?php

class Buildings
{
    private $data;
    private $database;
    private $group_id;

    function __construct() {
        // global import the database instance
        global $database;

        // reads from file
        $path_data = "/var/www/html/The-Wizard-Schools/src/assets/data/buildings.json";

        // initiates globals
        $this->database = $database;
        $this->group_id = $_GET["Team"];
        $this->get_json_data($path_data);
    }

    private function get_json_data(string $filename): void {
        // reads and formats a JSON-file
        $file = file_get_contents($filename);
        $this->buildings = json_decode($file, true);
    }

    private function get_id(string $name): int {
        // reads the building names and returns the index of the element
        $counter = 0;
        $this->search_for_name($name, $this->buildings, $counter);
        return $counter;
    }

    private function search_for_name($name, $knot, &$counter) {
        foreach($knot as $building_name => $building) {
            if($name === $building_name) { return 1; }

            $counter++;

            if($this->search_for_name($name, $building["children"], $counter) == 1) {
                return 1;
            }
        }
    }

    private function get_status(int $id): bool {
        // in the case that "none" is parent node it needs to return true
        if($id < 0) { return false; }
        // reads the building_repr from the corresponding group
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);

        // true => active; false => deactivated;
        return ($building_statuses[0]["buildings"] & pow(2, $id)) == pow(2, $id);
    }

    public function get_requirments(): array {
        foreach($this->buildings as $building_name => &$building) {
            $this->manipulate_building($building_name, $building, true);
        }

        return $this->buildings;
    }

    private function manipulate_building(string $building_name, array &$building, bool $parent_active): void {
        $building_active = $this->get_status($this->get_id($building_name));

        $building["active"] = $building_active;
        $building["parent_active"] = $parent_active;


        if($building["children"] !== "none") {
            foreach($building["children"] as $child_name => &$child) {
                $this->manipulate_building($child_name, $child, $building_active);
            }
        }
    }

    // CHANGING THE DATABASE BUILDING VALUE
    public function activate(string $building_name) {
        // aquries the building id from the name
        $building_id = $this->get_id($building_name);
        // aquires the building representation int
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_statuses[0]["buildings"];

        // checks for not exitens of the building
        if(!$this->get_status($building_id)) {
            // adds the building
            $building_repr += pow(2, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }

    public function deactivate(string $building_name) {
        // aquries the building id from the name
        $building_id = $this->get_id($building_name);
        // aquires the building representation int
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_statuses[0]["buildings"];

        // checks for exitens of the building
        if($this->get_status($building_id)) {
            // removes the building
            $building_repr -= pow(2, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }
}

?>
