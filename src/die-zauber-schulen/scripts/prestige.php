<?php

class Prestige
{
    function __construct() {
        global $database;

        $this->database = $database;
        $this->group_id = $_GET["Team"];
    }

    // === NECESSITIES ===
    public function get_requirements() {
        // aquires and returns the prestige value
        $prestige = $this->database->select_where("LABOUR", ["prestige"], ["group_id" => $this->group_id]);
        return $prestige[0]["prestige"];
    }

    public function get_teams_prestige() {
        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $prestige = array();

        foreach($file["general"]["teams"] as $group_id => $_) {
            $team_specific_prestige = $this->database->select_where("LABOUR", ["prestige"], ["group_id" => $group_id]);

            $prestige[intval($group_id)] = floatval($team_specific_prestige[0]["prestige"]);
        }

        return $prestige;
    }

    // === ACTIONS ===
    public function add_value(string $value): void {
        // aqurie the old prestige value
        $prestige = $this->database->select_where("LABOUR", ["prestige"], ["group_id" => $this->group_id]);

        // convert string types to integers
        $new_value = intval($value);
        $old_prestige = intval($prestige[0]["prestige"]);

        // add and push new value into database
        $new_prestige = $old_prestige + $new_value;
        $this->database->update("LABOUR", ["prestige" => $new_prestige], ["group_id" => $this->group_id]);
    }
}

?>
