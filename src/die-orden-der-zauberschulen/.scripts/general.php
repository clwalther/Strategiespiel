<?php

// TODO: fetch the value from .conf file
define('TEAMS', 'teams');
define('STUDENTS', 'students');
define('EXCHANGES', 'exchanges');
define('TRANSACTIONS', 'transactions');


class Display
{
    // DRAWERS
    public static function teams(): void {
        global $database;

        $teams = $database->select(TEAMS, ["*"]);

        foreach ($teams as $team) {
            $link = Document::create_element("a");
            $image = Document::create_element("img");
            $span = Document::create_element("span");

            $link->append_child($image);
            $link->append_child($span);

            $link->attributes["href"] = sprintf("./index.php?team=%s", $team["team_id"]);
            $link->attributes["target"] = "_self";
            $image->attributes["src"] = "/../.assets/icons/group.svg";
            $span->inner_text = sprintf("#%s - %s", $team["team_id"], $team["name"]);

            if(in_array("team", array_keys($_GET)) && $team["team_id"] == $_GET["team"]) { $link->add_class("active_link"); }

            echo $link->get_html();
        }
    }

    public static function exchanges(): void {
        global $database;

        $exchanges = $database->select(EXCHANGES, ["*"]);

        foreach ($exchanges as $exchange) {
            $link = Document::create_element("a");
            $image = Document::create_element("img");
            $span = Document::create_element("span");

            $link->append_child($image);
            $link->append_child($span);

            $link->attributes["href"] = sprintf("./index.php?exchange=%s", $exchange["exchange_id"]);
            $link->attributes["target"] = "_self";
            $image->attributes["src"] = "/../.assets/icons/trending-up.svg";
            $span->inner_text = sprintf("%s", $exchange["name"]);

            if(in_array("exchange", array_keys($_GET)) && $exchange["exchange_id"] == $_GET["exchange"]) { $link->add_class("active_link"); }

            echo $link->get_html();
        }
    }

    // ARTICLES
    public static function teamname(): void {
    }

    public static function exchangename(): void {
    }
}

?>
