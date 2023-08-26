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
        return false;
    }

    public function get_share(): float {
        return 1.0;
    }

    public function get_ressources(): array {
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        return $this->database->select_where("FIRE_OF_HOGWARTS", array_keys($file["events"]["fire-of-hogwarts"]["weights"]), ["group_id" => $this->group_id]);
    }

    public function get_weights(): array {
        $file = file_get_contents(DATA_FILE_PATH);
        $file = json_decode($file, true);

        return $file["events"]["fire-of-hogwarts"]["weights"];
    }

    // === ACTIONS ===
    public function start(string $value): void {
        $value = floatval($value);
    }

    public function stop(): void {

    }

    public function set_ressource(string $value): void {
        $bundel = explode(";", $value);

        $name = $bundel[0];
        $value = floatval($bundel[1]);
    }
}

?>
