<?php

class ExpensionTree
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
        $this->database->connect();
        // getting group id
        $this->group_id = intval($_GET["Team"]);
    }

    private function get_json_data(string $filename): array {
        // reads and formats a JSON-file
        $file = file_get_contents($filename);
        return json_decode($file, true);
    }

    private function get_building_id(string $name) {
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

    public function build(): string {
        // initilizind the main branches in html format
        $erweiterungsflügel = $this->build_level(0);
        $lange_galerie      = $this->build_level(1);
        $parkanlage         = $this->build_level(2);
        $besenhütte         = $this->build_level(3);

        // html container struct:
            // 0 <=> 1. Erweiterungsflügel
            // 1 <=> 2. Lange Galerie
            // 2 <=> 3. Parkanlage
            // 3 <=> 4. Besenhütte
        $html = "<div>%s</div>
                <div>%s</div>
                <div>%s</div>
                <div>%s</div>";

        // returning the formated html structure with filled in brancehs
        return sprintf($html, $erweiterungsflügel, $lange_galerie, $parkanlage, $besenhütte);
    }

    public function connect(): string {
        // creates the elements fromt which the arrows are made visible by javascript
        $connection_html = "";

        foreach($this->expansion_data["buildings"] as $building_name => $building) {
            if($building["parentNode"] != "none") {
                $parameter_value_structure = "%s:%s";
                $parameter_html_structure = "<param name='arrow' value='%s'>";

                $parameter_value = sprintf($parameter_value_structure, $building["parentNode"], $building_name);
                $connection_html .= sprintf($parameter_html_structure, $parameter_value);
            }
        }

        return $connection_html;
    }

    private function build_level(int $branch_index): string {
        $branch_html = "";

        // looping through the selected main branches
        foreach($this->expansion_data["branches"][$branch_index] as $levels) {
            $level_html = "";
            $level_html_struct = "<div>%s</div>";

            // looping through each level
            foreach($levels as $building_name) {
                // getting the building information from the array
                $building_data = $this->expansion_data["buildings"][$building_name];
                // html stencil
                $button_html_struct = "<button %s id='%s' onclick='open_dialog(`dialog-%s`);'>%s</button>";
                $button_status = $this->collabse_button_status($building_name);

                // add the completed button html to the level html string
                $level_html .= sprintf($button_html_struct, $button_status, $building_name, $building_name, $building_data["trivialname"]);
            }

            // add the completed level html to the branch html string
            $branch_html .= sprintf($level_html_struct, $level_html);
        }

        return $branch_html;
    }

    private function collabse_button_status(string $building_name): string {
        // database access
        $database_return = $this->database->select_where("MINISTRY_SCHOOL_ADMIN", ["buildings"], ["group_id" => $this->group_id]);
        $database_building_info = $database_return[0]["buildings"];
        $building = $this->expansion_data["buildings"][$building_name];

        if($this->check_building_repre($building_name, $database_building_info)) {
            // if building id is part of the representation string, we want to enable the button visually with the class: "enabled"
            $button_status = "class='enabled'";
        } else if($building["parentNode"] == "none" or $this->check_building_repre($building["parentNode"], $database_building_info)) {
            // if not the building but instead the parent node is enabled we do not want anything to happen
            $button_status = "";
        } else {
            // if all fails we want the button to generally disabled
            $button_status = "disabled";
        }

        return $button_status;
    }

    private function check_building_repre(string $building_name, int $repr) {
        // === START CONST ===
        $states = 2; // states: (on; off) -> n = 2
        // === END CONST ===
        $building_id = $this->get_building_id($building_name);
        $is_included = (pow($states, $building_id) & $repr) == pow($states, $building_id);

        return $is_included;
    }
}

?>
