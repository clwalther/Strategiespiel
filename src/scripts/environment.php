<?php

class Environment
{
    private $file;
    private $environment;

    function __construct(string $filename) {
        $this->file = file($filename);
        $this->format();
    }

    private function format(): void {
        echo var_dump($this->file);
    }

    public function access(string $key): string {
        return $environment[$key];
    }
}

?>
