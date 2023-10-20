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
        global $database;

        $team = $database->select_where(TEAMS, ["name"], ["team_id" => $_GET["team"]]);

        echo "#".$_GET["team"]." - ".$team[0]["name"];
    }

    public static function exchangename(): void {
        global $database;

        $exchange = $database->select_where(EXCHANGES, ["name"], ["exchange_id" => $_GET["exchange"]]);

        echo $exchange[0]["name"]." - <i>tradevalue</i>";
    }

    // DIALOGS
    public static function dialog_start_resume(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("start-resume", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Start or resume game";
        $paragraph->inner_text = "Starts background process. CAUTION: this will have immediate affects!";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Start / Resume";

        echo $dialog->get_html();
    }

    public static function dialog_stop_pause(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("stop-pause", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Stop or pause game";
        $paragraph->inner_text = "This will halt all background process.";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Stop / Pause";

        echo $dialog->get_html();
    }

    public static function dialog_backup(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("backup", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Backup";
        $paragraph->inner_text = 'This will create a backup of all tables of the database "die-orden-der-zauber-schulen".';
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Backup";

        echo $dialog->get_html();
    }

    public static function dialog_load_backup(): void {
        $dialog = Document::create_dialog("load-backup", "0");

        $dialog->header->inner_text = "Load Backup";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Load backup";

        echo $dialog->get_html();
    }
}

?>
