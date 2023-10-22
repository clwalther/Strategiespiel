<?php

// [ ] TODO: fetch the value from .conf file
define('TEAMS', 'teams');
define('STUDENTS', 'students');
define('EXCHANGES', 'exchanges');
define('TRANSACTIONS', 'transactions');


class DisplayGeneral
{
    // DRAWERS
    public static function create_drawer_teams(): void {
        global $database;

        // fetch all teams
        $teams = $database->query(sprintf("SELECT * FROM %s ORDER BY %s ASC", TEAMS, "team_id"));

        foreach ($teams as $team) {
            // create html elements
            $link = Document::create_element("a");
            $image = Document::create_element("img");
            $span = Document::create_element("span");

            // append child
            $link->append_child($image);
            $link->append_child($span);

            // element conf
            $link->attributes["href"] = sprintf("./index.php?team=%s", $team["team_id"]);
            $link->attributes["target"] = "_self";
            $image->attributes["src"] = "/../.assets/icons/group.svg";
            $span->inner_text = sprintf("#%s - %s", $team["team_id"], $team["name"]);

            // add active
            if(in_array("team", array_keys($_GET)) && $team["team_id"] == $_GET["team"]) { $link->add_class("active_link"); }

            echo $link->get_html();
        }
    }

    public static function create_drawer_exchanges(): void {
        global $database;

        // fetch all exchanges
        $exchanges = $database->query(sprintf("SELECT * FROM %s ORDER BY %s ASC", EXCHANGES, "exchange_id"));

        foreach ($exchanges as $exchange) {
            // create html elements
            $link = Document::create_element("a");
            $image = Document::create_element("img");
            $span = Document::create_element("span");

            // append child
            $link->append_child($image);
            $link->append_child($span);

            // element conf
            $link->attributes["href"] = sprintf("./index.php?exchange=%s", $exchange["exchange_id"]);
            $link->attributes["target"] = "_self";
            $image->attributes["src"] = "/../.assets/icons/trending-up.svg";
            $span->inner_text = sprintf("#%s - %s", $exchange["exchange_id"], $exchange["name"]);

            // add active
            if(in_array("exchange", array_keys($_GET)) && $exchange["exchange_id"] == $_GET["exchange"]) { $link->add_class("active_link"); }

            echo $link->get_html();
        }
    }

    // ARTICLES
    public static function create_h1_teamname(): void {
        global $database;

        // fetch currnet team
        $team = $database->query(sprintf("SELECT * FROM %s WHERE %s = %s", TEAMS, "team_id", $_GET["team"]));

        // create and conf html element
        $heading_1 = Document::create_element("h1");
        $heading_1->inner_text = sprintf("#%s - %s", $team[0]["team_id"], $team[0]["name"]);

        echo $heading_1->get_html();
    }

    public static function create_h1_exchangename(): void {
        global $database;

        // fetch currnet exchange
        $exchange = $database->query(sprintf("SELECT * FROM %s WHERE %s = %s", EXCHANGES, "exchange_id", $_GET["exchange"]));

        // create and conf html element
        $heading_1 = Document::create_element("h1");
        $heading_1->inner_text = sprintf("#%s - %s", $exchange[0]["exchange_id"], $exchange[0]["name"]);

        echo $heading_1->get_html();
    }

    // DIALOG
    public static function create_dialog_change_teamname(): void {
        global $database;


    }
}

?>
