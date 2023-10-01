<?php

class MinistryOfLabourDisplay
{
    function __construct() {

    }

    public static function start_resume_event_fire_of_hogwarts() {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("start-resume-event", "fire-of-hogwarts");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = 'Start or resume event "Brand von Hogwarts"';
        $paragraph->inner_text = "This will enable payment for event. CAUTION: this will have immediate affects!";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Start / Resume event";

        echo $dialog->get_html();
    }

    public static function stop_pause_event_fire_of_hogwarts() {
        $paragraph = Document::create_element("p");
        $dialog = Document::create_dialog("stop-pause-event", "fire-of-hogwarts");
        $dialog->container->append_child($paragraph);

        $dialog->header->inner_text = 'Stop or pause event "Brand von Hogwarts"';
        $paragraph->inner_text = "This will enable payment for event. CAUTION: this will have immediate affects!";
        $dialog->submit->attributes["onclick"] = "close_dialog();";
        $dialog->submit->inner_text = "Stop / Pause event";

        echo $dialog->get_html();
    }
}

?>
