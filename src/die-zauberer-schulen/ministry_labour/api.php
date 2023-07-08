<?php

include "../scripts/global.php";

function get_progress_array(int $identifier): array {
    $progress_array = [];
    for($n_category=0; $n_category < N_JOBS; $n_category++) {
        array_push($progress_array, get_value_form_integer($identifier, $n_category, MAX_JOB_INFLUENCE));
    }
    return $progress_array;
}

function create_json_formate($query_respone): string {
    $json_string = '{%s}';
    $group_array = [];

    foreach($query_respone as $respone) {
        $progress_array = get_progress_array(intval($respone["job_influence"]));
        $progress_string = implode(", ", $progress_array);
        $group_string = sprintf('"%s": [%s]', $respone["group_id"], $progress_string);
        array_push($group_array, $group_string);
    }

    return sprintf($json_string, implode(", ", $group_array));
}

$database = new Database();
$database->connect();
$query_respone = $database->select("MINISTRY_LABOUR", ["*"]);
$database->close();

echo create_json_formate($query_respone);

?>
