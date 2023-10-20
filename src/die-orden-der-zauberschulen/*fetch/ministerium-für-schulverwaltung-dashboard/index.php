<?php

    /**
     * This returns a json file for the dashboard of "Ministerium für Schulverwaltung"
     */
    header('Content-Type: application/json; charset=utf-8');

    echo file_exists("LOG_FILE") ? json_encode(file("LOG_FILE", FILE_IGNORE_NEW_LINES)) : json_encode(["FATAL ERROR: could not aquire 'Ministerium für Schulverwaltung'"]);
?>
