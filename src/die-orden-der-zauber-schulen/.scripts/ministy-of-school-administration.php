<?php

class MinistryOfSchoolAdministrationDisplay
{
    function __construct() {

    }

    public static function n_unfetched_students() {
        global $database;

        $students = $database->select_where(STUDENTS, ["id"], ["group_id" => $_GET["team"]]);

        echo sizeof($students);
    }

    public static function teachers() {
        global $database;

        // $teachers = ;

        // foreach($teachers as $teacher) {
        //     $teacher_panel = Document::create_panel($teacher["job_name"])

        //     echo $teacher->get_html();
        // }
    }
}

?>
