<?php

class DisplayMinistryOfLabour
{
    function __construct() {

    }

    public static function create_dialog_event_fire_of_hogwarts_start_resume(): void {
        // create html elements
        $dialog = Document::create_dialog("event-fire_of_hogwarts-start-resume", 0);
        $paragraph = Document::create_element("p");

        // append child
        $dialog->container->append_child($paragraph);

        // dialog conf
        $dialog->header->inner_text = "Event <q>Brand von Hogwarts</q> Starten oder Fortfahren";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Start / Fortfahren";

        $paragraph->inner_text = "Diese Aktion aktiviert Event and einzahlung für das Event. <caution><b>VORSICHT</b>: Diese Aktion hat direkte und möglicherweise schwerwiegende Konsequenzen!</caution>";

        echo $dialog->get_html();
    }

    public static function create_dialog_event_fire_of_hogwarts_stop_pause(): void {
        // create html elements
        $dialog = Document::create_dialog("event-fire_of_hogwarts-stop-pause", 0);
        $paragraph = Document::create_element("p");

        // append child
        $dialog->container->append_child($paragraph);

        // dialog conf
        $dialog->header->inner_text = "Event <q>Brand von Hogwarts</q> Stoppen oder Pausieren";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Stop / Pause";

        $paragraph->inner_text = "Diese Aktion deaktiviert Event and einzahlung für das Event. <caution><b>VORSICHT</b>: Diese Aktion hat direkte und möglicherweise schwerwiegende Konsequenzen!</caution>";

        echo $dialog->get_html();
    }
}

?>
