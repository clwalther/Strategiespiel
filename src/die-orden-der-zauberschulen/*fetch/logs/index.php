<?php

    /**
     * This returns a json file of the
     */
    header('Content-Type: application/json; charset=utf-8');

    include "/var/www/html/Strategiespiel/src/.scripts/database.php";
    include "/var/www/html/Strategiespiel/src/.scripts/environment.php";

    $conf_folder_name = explode("/", $_SERVER['PHP_SELF'])[1];
    $conf_folder_path = "/var/www/html/Strategiespiel/conf.d/";

    $environment = new EnvironmentHandler($conf_folder_path.$conf_folder_name."/.env");

    $database = new DatabaseHandler(
        $environment->get("DATABASE_NAME"),
        $environment->get("SERVERNAME"),
        $environment->get("USER_LOGIN"),
        $environment->get("USERNAME")
    );

    $database->connect();

    $response = $database->query(sprintf(
        "SELECT time_stamp, message_type, context FROM logs ORDER BY time_stamp, log_id;"
    ));

    echo json_encode($response);
?>
