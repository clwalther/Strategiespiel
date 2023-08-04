<?php

class General
{
    function __construct() {
        global $database;

        $this->database = $database;
    }

    public function reset(): void {
        $this->database->delete_all("TIME"); // resets time: by delete every entry
        $this->database->update("TEAM", ["teamname" => "TEAMNAME"], ["*"]); // reset team: by defaulting every entry
        $this->database->update("MINISTRY_SCHOOL_ADMIN", [
            "Zaubertränke" => 0,
            "Zauberkunst"  => 0,
            "Verteidigung" => 0,
            "Geschichte"   => 0,
            "Geschöpfe"    => 0,
            "Kräuterkunde" => 0,
            "Besenfliegen" => 0,
            "buildings"    => 0
        ], ["*"]); // reset ministry of school administration: by defaulting every entry
    }

    public function start(): void {
        // writes a start time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => true]);
    }

    public function pause(): void {
        // writes a halt/stop time log into time-database
        $this->database->insert("TIME", ["time" => time(), "type" => false]);
    }

    public function get_times(): array {
        // reqads all time logs from database
        $time_logs = $this->database->select("TIME", ["time", "type"]);

        // 1.: gets the last type from database to determine wheter halted or not
        // 2.: writes all of the time logs into time logs send object
        $send_times = [
            "is_running" => end($time_logs)["type"],
            "times" => $time_logs
        ];

        // returns the time array object
        return $send_times;
    }

    public function get_teams(): array {
        // reads and returns the team array object
        return $this->database->select("TEAM", ["*"]);
    }

    public function change_name($value): void {
        // changes the name on a team
        $id = explode(";", $value)[0];
        $name = explode(";", $value)[1];

        $this->database->update("TEAM", ["teamname" => $name], ["group_id" => $id]);
    }
}

?>
