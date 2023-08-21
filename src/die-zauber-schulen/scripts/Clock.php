<?php

class Clock
{
    function __construct() {
        global $database;
        global $general;

        $this->database = $database;
        $this->general = $general;

        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $this->file = json_decode($file, true);

        // init array
        $this->time_array = array();
    }

    public function start(): void {
        echo "starting loop...\n\n";

        $this->step($this->time_array);
    }

    public function create_element(string $key, float $start_time, string $aqurie_time_function, string $event_function): void {
        $this->time_array[$key] = [
            "time" => $start_time,
            "aquire" => $aqurie_time_function,
            "function" => $event_function
        ];
    }

    private function step(array $time_array): void {
        // checks for if game is still running
        if($this->is_running()) {
            // formatting the output
            echo "updating:\n";

            // loops thorugh all teams given by times
            foreach($time_array as $key => &$object) {
                // if time is zero meaning the function was called by item
                if($object["time"] == 0) {
                    // calling the event function
                    $object["function"]($key);

                    // gets the new time for expired group
                    $object["time"] = $object["aquire"]($key);

                    // prints the team that has been updated
                    echo sprintf(" %s;", $key);
                }
            }

            // get minimum time
            $min_time = $this->get_min_time($time_array);

            // formatting the output
            echo sprintf("\n\nwaiting for: %s min...\n\n", $min_time);

            // sleeps the given minimum time [seconds] => [minutes]
            sleep($min_time * 60);

            // manipulating for next step
            $this->update_times($min_time, $time_array);
            $this->step($time_array);
        } else {
            // exit output
            echo "aborting loop... [DATABASE::CALL]\n\n";
        }

    }

    private function is_running(): bool {
        // aquires the time logs from database
        $time_logs = $this->database->select("TIME", ["type"]);
        // take the last log and checks type
        return intval(end($time_logs)["type"]) == 1;
    }

    private function update_times(float $delta, array &$time_array): void {
        // loping through all items in times
        foreach($time_array as $key => &$value) {
            // subtracting the timedelta form  each time
            $value["time"] -= $delta;
        }
    }

    private function get_min_time(array $time_array): float {
        // returns the min time value of the main array
        $times = array();

        // extracts the time value out of the main array
        foreach ($time_array as $key => $value) {
            // pushes the value in times
            array_push($times, $value["time"]);
        }

        // returns the min value of times
        return min($times);
    }
}

?>
