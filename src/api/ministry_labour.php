<?php

include "../scripts/global.php";

function return_json_format($query_respone) {

}

$database = new Database();
$database->connect();
$query_respone = $database->select("MINISTRY_LABOUR", ["*"]);
$database->close();

return_json_format($query_respone);

?>
