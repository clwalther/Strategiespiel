<?php

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

        // fetch currnet team
        $team = $database->query(sprintf("SELECT %s FROM %s WHERE %s = %s", "name", TEAMS, "team_id", $_GET["team"]))[0];


        // create html elements
        $dialog = Document::create_dialog("change-teamname", 0);
        $paragraph = Document::create_element("p");
        $input = Document::create_element("input");

        $dialog->container->append_child($paragraph);
        $dialog->container->append_child($input);

        // dialog conf
        $dialog->header->inner_text = "Ändere Teamname";
        $dialog->submit->attributes["onclick"] = "";
        $dialog->submit->inner_text = "Ändern";

        // input conf
        $input->attributes["type"] = "text";
        $input->attributes["placeholder"] = "Ändere Teamname";
        $input->attributes["value"] = $team["name"];

        // paragraph conf
        $paragraph->inner_text = "Teamname änderungen haben <b>keine</b> schwerwiegenden Konsequenzen für das Team oder das Insgesamte Spiel. Es is erlaubt Teams den geleichen Namen zu geben und so weiter. Die einzige Konsequenz ist eine der visuellen Natur. Hierfür wird vorgeschlagen Namen nicht zulang werden zu lassen.";

        echo $dialog->get_html();
    }

    // SKILL
    public static function create_skill_card(float $base_skill, float $advanced_skill, bool $edit_base, bool $edit_advanced): Element {
        global $configuration;

        // create html elements
        $skill_card = Document::create_element("div");
        $skill_card->add_class("skill-card");

        for ($subject_index = 0; $subject_index < count($configuration->general->subjects); $subject_index++) {
            // create html elements
            $skill_line = Document::create_element("div");
            $skill_span = Document::create_element("span");
            $skill_rating = Document::create_element("div");
            $skill_base_rating = Document::create_element("div");
            $skill_advanced_rating = Document::create_element("div");

            // append elements to parent
            $skill_card->append_child($skill_line);
            $skill_line->append_child($skill_span);
            $skill_line->append_child($skill_rating);
            $skill_rating->append_child($skill_base_rating);
            $skill_rating->append_child($skill_advanced_rating);

            // conf
            $skill_span->inner_text = $configuration->general->subjects[$subject_index];

            // base skill rating
            for ($base_rating_index = 0; $base_rating_index < $configuration->general->base_skill; $base_rating_index++) {
                // create html buttons
                $skill_rating_btn = Document::create_element("input");

                // append to child to parent
                $skill_base_rating->append_child($skill_rating_btn);

                // conf
                $skill_rating_btn->attributes["type"] = "checkbox";
                if ($base_rating_index < $subject_index) { $skill_rating_btn->attributes["checked"] = "true"; }
                if (!$edit_base) { $skill_rating_btn->attributes["disabled"] = "true"; }
            }

            // advanced skill rating
            for ($advanced_rating_index = 0; $advanced_rating_index < $configuration->general->advanced_skill; $advanced_rating_index++) {
                // create html buttons
                $skill_rating_btn = Document::create_element("input");

                // append to child to parent
                $skill_advanced_rating->append_child($skill_rating_btn);

                // conf
                $skill_rating_btn->attributes["type"] = "checkbox";
                if ($advanced_rating_index < $subject_index) { $skill_rating_btn->attributes["checked"] = "true"; }
                if (!$edit_advanced) { $skill_rating_btn->attributes["disabled"] = "true"; }
            }
        }

        return $skill_card;
    }
}

?>
