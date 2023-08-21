<?php

class Buildings
{
    function __construct() {
        // global import the database instance
        global $database;
        global $general;
        global $utils;

        // initiates globals
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

        $root = $file["buildings"];

        // manipulates the whole tree by the root
        $this->manipulate_knot($root, true, $this->group_id);

        // returns the manipulated tree
        return $root;
    }

    private function manipulate_knot(array &$knot, bool $parent_active, int $group_id): void {
        // loops through all buildings in a knot
        foreach($knot as $building_name => &$building) {
            // aquire building information
            $building_id = $this->utils->get_building_id($building_name);
            $building_active = $this->utils->get_building_status($building_id, $group_id);

            // write information into pointer
            $building["active"] = $building_active;
            $building["parent_active"] = $parent_active;

            // aquire the f(x+1) generation
            $filial_knot = &$building["children"];

            // checks if knot is authentic
            if($filial_knot != "none") {
                // manipulates the next branch forming from the knot
                $this->manipulate_knot($filial_knot, $building_active, $group_id);
            }
        }
    }

    public function get_teams_progress(): array {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $teams = array();

        // looping through all teams
        foreach($file["general"]["teams"] as $group_id => $_) {
            $root = $file["buildings"];

            // manipulates the whole tree by the root
            $this->manipulate_knot($root, true, $group_id);

            // returns the manipulated tree
            $teams[intval($group_id)] = $root;
        }

        return $teams;
    }

    // === ACTIONS ===
    public function activate(string $building_name) {
        // activates a building
        // aquries the building id from the name
        $building_id = $this->utils->get_building_id($building_name);
        // aquires the building representation int
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_statuses[0]["buildings"];

        // checks for not exitens of the building
        if(!$this->utils->get_building_status($building_id, $this->group_id)) {
            // adds the building
            $building_repr += pow(BUILDING_STATES, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }

    public function deactivate(string $building_name) {
        // deactivates a building
        // aquries the building id from the name
        $building_id = $this->utils->get_building_id($building_name);
        // aquires the building representation int
        $building_reprs = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_reprs[0]["buildings"];

        // checks for exitens of the building
        if($this->utils->get_building_status($building_id, $this->group_id)) {
            // removes the building
            $building_repr -= pow(BUILDING_STATES, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }
}

?>
