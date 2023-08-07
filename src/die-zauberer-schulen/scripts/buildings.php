<?php

class Buildings
{
    function __construct() {
        // global import the database instance
        global $database;

        // initiates globals
        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    // === METHODS ===
    private function get_id(string $name): ?int {
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

    private function get_status(int $id): bool {
        // aquiers the status of certain building id
        // in the case that "none" is parent node it needs to return true
        if($id < 0) { return false; }
        // reads the building_repr from the corresponding group
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);

        // true => active; false => deactivated;
        return ($building_statuses[0]["buildings"] & pow(BUILDING_STATES, $id)) == pow(BUILDING_STATES, $id);
    }

    // === NECESSITIES ===
    public function get_requirements(): array {
        // returns the necessities for the frontend
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $root = $file["buildings"];

        // manipulates the whole tree by the root
        $this->manipulate_knot($root, true);

        // returns the manipulated tree
        return $root;
    }

    private function manipulate_knot(array &$knot, bool $parent_active): void {
        // loops through all buildings in a knot
        foreach($knot as $building_name => &$building) {
            // aquire building information
            $building_id = $this->get_id($building_name);
            $building_active = $this->get_status($building_id);

            // write information into pointer
            $building["active"] = $building_active;
            $building["parent_active"] = $parent_active;

            // aquire the f(x+1) generation
            $filial_knot = &$building["children"];

            // checks if knot is authentic
            if($filial_knot != "none") {
                // manipulates the next branch forming from the knot
                $this->manipulate_knot($filial_knot, $building_active);
            }
        }
    }

    // === ACTIONS ===
    public function activate(string $building_name) {
        // activates a building
        // aquries the building id from the name
        $building_id = $this->get_id($building_name);
        // aquires the building representation int
        $building_statuses = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_statuses[0]["buildings"];

        // checks for not exitens of the building
        if(!$this->get_status($building_id)) {
            // adds the building
            $building_repr += pow(BUILDING_STATES, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }

    public function deactivate(string $building_name) {
        // deactivates a building
        // aquries the building id from the name
        $building_id = $this->get_id($building_name);
        // aquires the building representation int
        $building_reprs = $this->database->select_where("SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $building_repr = $building_reprs[0]["buildings"];

        // checks for exitens of the building
        if($this->get_status($building_id)) {
            // removes the building
            $building_repr -= pow(BUILDING_STATES, $building_id);

            // updates the database
            $this->database->update("SCHOOL_ADMIN", ["buildings" => $building_repr], ["group_id" => $this->group_id]);
        }
    }
}

?>
