<?php

class EnvironmentHandler
{
    private $file_contents;
    private $file_path;

    function __construct(string $file_path) {
        $this->file_path = $file_path;

        $this->read_file();
    }

    public function get(string $key): string {
        return $this->file_contents[$key];
    }

    private function read_file(): void {
        // Using the optional flags parameter
        $file = file($this->file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($file as $line_index => $line) {
            $split_line = explode("=", $line);
            $key   = trim($split_line[0]);
            $value = trim($split_line[1]);

            $this->file_contents[$key] = $value;
        }
    }
}

?>
