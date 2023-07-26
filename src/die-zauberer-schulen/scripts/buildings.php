<?php


class Buildings
{
    private $expansion_data;
    private $database;
    public $group_id;

    function __construct() {
        // global import the database instance
        global $database;
        // based on file: /var/www/html/The-Wizard-Schools/src/assets/data/requirments.json
        $path_to_expansion_data = "/var/www/html/The-Wizard-Schools/src/assets/data/requirments.json";

        // reading file and formating to php-array
        $this->expansion_data = $this->get_json_data($path_to_expansion_data)["schulverwaltung"];
        // setting up database
        $this->database = $database;

        $this->group_id = $_GET["Team"];
    }

    private function get_json_data(string $filename): array {
        // reads and formats a JSON-file
        $file = file_get_contents($filename);
        return json_decode($file, true);
    }

    private function get_building_id(string $name): int {
        // converts an name to the corresponding id of the building
        $index = 0;

        // generally loops through all list until given entry is found
        // looping through main branches
        foreach($this->expansion_data["branches"] as $main_branches) {
            // looping through level in main branch
            foreach($main_branches as $levels) {
                // looping through building name in level
                foreach($levels as $building_name) {
                    // checking for matching name
                    if($building_name == $name) {
                        // returning the correct id
                        return $index;
                    } else {
                        $index++;
                    }
                }
            }
        }

        // returning -1 when there was no matching entry
        return -1;
    }

    private function get_building_status(int $id): bool {
        // true => active; false => deactivated;
        $table = "SCHOOL_ADMIN";
        $columns = ["buildings"];
        $condition = ["group_id" => $this->group_id];

        // reads the building_repr from the corresponding group
        $building_statuses = $this->database->select_where($table, $columns, $condition);

        if($id == -1) { return true; } // in the case that none is parent node it needs to return true

        return (floatval($building_statuses[0]["buildings"]) & pow(2, $id)) == pow(2, $id);
    }

    public function get_requirments(): array {
        $send_array = [];

        // looping through main branches
        foreach($this->expansion_data["branches"] as $main_branch) {
            $send_main_branch = [];

            // looping through levels in main branch
            foreach($main_branch as $level) {
                $send_level = [];

                // looping through buildings in level
                foreach($level as $building_name) {
                    // get buildings object from requirments
                    $building = $this->expansion_data["buildings"][$building_name];
                    // get building id
                    $building_id = $this->get_building_id($building_name);
                    // team array object
                    $send_building = [
                        "id" => $building_id,
                        "name" => $building["trivialname"],
                        "parent" => $building["parent"],
                        "active" => $this->get_building_status($building_id),
                        "parent_active" => $this->get_building_status($this->get_building_id($building["parent"])),

                        "requriments" => $building["requirements"],
                        "yields" => $building["yields"]
                    ];

                    array_push($send_level, $send_building);
                }
                array_push($send_main_branch, $send_level);
            }
            array_push($send_array, $send_main_branch);
        }

        return $send_array;
    }

    public function activate($id) {
        $table_name = "SCHOOL_ADMIN";
        $conditions = ["group_id" => $this->group_id];
        $columns = ["buildings"];

        // aquires the building representation int
        $building_statuses = $this->database->select_where($table_name, $columns, $conditions);
        $building_repr = floatval($building_statuses[0]["buildings"]);

        // checks for exitens of the building
        if(($building_repr & pow(2, $id)) != pow(2, $id)) {
            // adds the building
            $building_repr += pow(2, $id);

            // updates the database
            $this->database->update($table_name, ["buildings" => $building_repr], $conditions);
        }
    }

    public function deactivate($id) {
        $table_name = "SCHOOL_ADMIN";
        $conditions = ["group_id" => $this->group_id];
        $columns = ["buildings"];

        // aquires the building representation int
        $building_statuses = $this->database->select_where($table_name, $columns, $conditions);

        // checks for exitens of the building
        $building_repr = floatval($building_statuses[0]["buildings"]);

        if(($building_repr & pow(2, $id)) == pow(2, $id)) {
            // removes the exitens of the building
            $building_repr -= pow(2, $id);

            // updates the database
            $this->database->update($table_name, ["buildings" => $building_repr], $conditions);
        }
    }
}

?>
