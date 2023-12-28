<?php

    /**
     * This compiles and executes the commands that has been provided by
     * the post request.
     */
    header('Content-Type: text/plain; charset=utf-8');

    include "/var/www/html/Strategiespiel/src/die-orden-der-zauberschule/.scripts/general.php";
    include "/var/www/html/Strategiespiel/src/die-orden-der-zauberschule/.scripts/ministry-of-school-administration.php";
    include "/var/www/html/Strategiespiel/src/die-orden-der-zauberschule/.scripts/ministry-of-labour.php";

    // get the post body
    $raw_command = file_get_contents('php://input');

    foreach (explode(";", $raw_command) as $command) {
        // check for command in line
        if (strlen($command) > 0) {
            // extract information from line
            $split_command = explode(" ", strtoupper(trim($command)));

            // compiler and executer
            switch ($split_command[0]) {
                case 'START':
                    // this starts the mainloop or events
                    switch ($split_command[1]) {
                        case 'GAME':
                            // mainloop
                            raise_ok();
                            break;

                        case 'EVENT':
                            // events
                            raise_ok();
                            break;

                        default:
                            raise_error("Unkown");
                            break;
                    }
                    break;

                case 'TEAMNAME':
                    switch ($split_command[1]) {
                        case 'SET':
                            raise_ok();
                            break;

                        default:
                            raise_error("Unkown");
                            break;
                    }
                    break;

                default:
                    raise_error("Unkown");
                    break;
            }
        }
    }

    function raise_ok(): void {
        echo "OK";
    }
    function raise_error(string $error): void {
        echo sprintf("ERROR: %s", $error);
    }
?>
