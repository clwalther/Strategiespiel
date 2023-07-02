<?php

function add_to_integer(int $current, int $add_points, int $n_category, int $max_points): int {
    return $current + $add_points * pow($max_points + 1, $n_category);
}

function get_value_form_integer(int $current, int $n_category, int $max_points): int {
    return floor($current / pow($max_points, $n_category)) - floor(pow($max_points, $n_category - 1)) * pow($max_points, $n_category);
}

?>
