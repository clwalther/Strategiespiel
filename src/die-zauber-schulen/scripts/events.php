<?php

class Fire_of_Hogwarts
{
    function __construct() {
        global $database;

        $this->database = $database;

        $this->group_id = $_GET["Team"];
    }

    // === NECESSITIES ===
    public function is_enabled(): bool {
        $answer = end($this->database->select_where("EVENT", ["duration", "time"], ["name" => "FIRE_OF_HOGWARTS"]));

        return time() < intval($answer["time"]) + floatval($answer["duration"]) * 60;
    }

    public function get_time_ratio(): float {
        $answer = end($this->database->select_where("EVENT", ["duration", "time"], ["name" => "FIRE_OF_HOGWARTS"]));

        if(floatval($answer["duration"]) * 60 != 0) {
            return (intval($answer["time"]) + floatval($answer["duration"]) * 60 - time()) / (floatval($answer["duration"]) * 60);
        } else {
            return 0;
        }
    }

    public function get_share(): float {
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $answers = $this->database->select("FIRE_OF_HOGWARTS", array_keys($file["events"]["fire-of-hogwarts"]["weights"]));

        $total_points = 0;
        $group_points = 0;

        foreach($answers as $answer) {
            foreach($file["events"]["fire-of-hogwarts"]["weights"] as $ressouce => $multiplier) {
                $total_points += floatval($answer[$ressouce]) * floatval($multiplier);
            }
        }

        $group_specific_answer = $this->database->select_where("FIRE_OF_HOGWARTS", array_keys($file["events"]["fire-of-hogwarts"]["weights"]), ["group_id" => $this->group_id])[0];

        foreach($file["events"]["fire-of-hogwarts"]["weights"] as $ressouce => $multiplier) {
            $group_points += floatval($group_specific_answer[$ressouce]) * $multiplier;
        }

        if($total_points != 0) {
            return $group_points/$total_points;
        } else {
            return 0;
        }
    }

    public function get_ressources(): array {
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        return $this->database->select_where("FIRE_OF_HOGWARTS", array_keys($file["events"]["fire-of-hogwarts"]["weights"]), ["group_id" => $this->group_id])[0];
    }

    public function get_points(): array {
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        $columns = array_keys($file["events"]["fire-of-hogwarts"]["weights"]);
        array_push($columns, "group_id");

        $answers = $this->database->select("FIRE_OF_HOGWARTS", $columns);

        $results = array();

        foreach($answers as $answer) {
            $results[$answer["group_id"]] = 0;

            foreach($file["events"]["fire-of-hogwarts"]["weights"] as $ressouce => $multiplier) {
                $results[$answer["group_id"]] += floatval($answer[$ressouce]) * floatval($multiplier) * 100;
            }
        }
        return $results;
    }

    // === ACTIONS ===
    public function start(string $value): void {
        $value = floatval($value);

        $this->database->insert("EVENT", ["name" => "FIRE_OF_HOGWARTS", "time" => time(), "duration" => $value]);
    }

    public function stop(): void {
        $this->database->delete("EVENT", ["name" => "FIRE_OF_HOGWARTS"]);
    }

    public function set_ressource(string $value): void {
        $bundel = explode(";", $value);

        $name = $bundel[0];
        $value = floatval($bundel[1]);

        $this->database->update("FIRE_OF_HOGWARTS", [$name => $value], ["group_id" => $this->group_id]);
    }
}

?>
