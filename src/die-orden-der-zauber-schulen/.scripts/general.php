<?php

// TODO: fetch the value from .conf file
define('TEAMS', 'TEAM');
define('STUDENTS', 'STUDENTS');


class Display
{
    public static function teams(): void {
        global $database;

        $teams = $database->select(TEAMS, ["*"]);

        foreach ($teams as $team) {
            $button = Document::create_element("button");
            $image = Document::create_element("img");
            $span = Document::create_element("span");

            $button->append_child($image);
            $button->append_child($span);

            $button->attributes["onclick"] = sprintf("window.open('./index.php?team=%s', '_self');", $team["group_id"]);
            $image->attributes["src"] = "/../.assets/icons/group.svg";
            $span->inner_text = sprintf("#%s - %s", $team["group_id"], $team["teamname"]);

            if($team["group_id"] == $_GET["team"]) { $button->add_class("active_button"); }

            echo $button->get_html();
        }
    }

    public static function teamname(): void {
        global $database;

        $team = $database->select_where(TEAMS, ["teamname"], ["group_id" => $_GET["team"]]);

        echo "#".$_GET["team"]." - ".$team[0]["teamname"];
    }

    public static function start_resume(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("start-resume", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Start or resume game";
        $paragraph->inner_text = "Starts background process. CAUTION: this will have immediate affects!";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Start / Resume";

        echo $dialog->get_html();
    }

    public static function stop_pause(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("stop-pause", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Stop or pause game";
        $paragraph->inner_text = "This will halt all background process.";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Stop / Pause";

        echo $dialog->get_html();
    }

    public static function backup(): void {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("backup", "0");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = "Backup";
        $paragraph->inner_text = 'This will create a backup of all tables of the database "die-orden-der-zauber-schulen".';
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Backup";

        echo $dialog->get_html();
    }

    public static function load_backup(): void {
        $dialog = Document::create_dialog("load-backup", "0");

        $dialog->header->inner_text = "Load Backup";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Load backup";

        echo $dialog->get_html();
    }
}

?>
