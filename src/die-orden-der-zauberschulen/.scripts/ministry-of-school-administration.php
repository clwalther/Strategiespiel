<?php

class DisplayMinistryOfSchoolAdministration
{
    // ARTICLES
    public static function create_code_unclaimed_students(): void {
        global $database;

        // fetch all students of team
        $students = $database->query(sprintf("SELECT * FROM %s WHERE team_id = %s", STUDENTS, $_GET["team"]));

        // create and conf html element
        $code = Document::create_element("code");
        $code->inner_text = count($students);

        echo $code->get_html();
    }

    public static function create_labels_teachers(): void {
        global $database;

        // fetch all teachers of team

    }

    public static function create_label_tree_buildings(): void {

    }

    // DIALOGS
    public static function create_dialog_unclaimed_students(): void {
        global $database;

        // fetch all students of team
        $students = $database->query(sprintf("SELECT * FROM %s WHERE team_id = %s", STUDENTS, $_GET["team"]));

        // create html elements
        $dialog = Document::create_dialog("students", 0);
        $container_container = Document::create_element("div");
        $student_container = Document::create_element("div");
        $navigation_container = Document::create_element("div");
        $student_btn_left = Document::create_element("button");
        $student_btn_right = Document::create_element("button");
        $student_btn_left_img = Document::create_element("img");
        $student_btn_right_img = Document::create_element("img");

        // append elements
        $dialog->container->append_child($student_btn_left);
        $dialog->container->append_child($container_container);
        $dialog->container->append_child($student_btn_right);
        $student_btn_left->append_child($student_btn_left_img);
        $student_btn_right->append_child($student_btn_right_img);
        $container_container->append_child($student_container);
        $container_container->append_child($navigation_container);

        // conf dialog
        $dialog->header->inner_text = "Unabgeholte Schüler";
        $dialog->submit->attributes["onclick"] = "send(';');";
        $dialog->submit->inner_text = "Auszahlen";
        $dialog->submit->style["background-color"] = "var(--colour-red);";

        // conf buttons
        $student_btn_left_img->attributes["src"] = "/.assets/icons/arrow_left.svg";
        $student_btn_right_img->attributes["src"] = "/.assets/icons/arrow_right.svg";

        foreach ($students as $student) {
            // create and conf html element
            $student_card = DisplayGeneral::create_skill_card($student["value"], 0, false, false);
            $navigation_button = Document::create_element("button");

            // append skil card for the student
            $student_container->append_child($student_card);
            $navigation_container->append_child($navigation_button);
        }

        echo $dialog->get_html();
    }
}

?>
