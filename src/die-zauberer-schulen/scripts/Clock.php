<?php

include "../../scripts/global.php";
include "./general.php";

$general = new General();

include "./GraduatesCalculator.php";
include "./InfluenceCalculator.php";
include "./PrestigeDistributor.php";

$graduates_calc = new GraduatesCalculator();
$influence_calc = new InfluenceCalculator();
$prestige_dist = new PrestigeDistributer();

class Clock
{
    function __construct() {

    }
}

$database->close();

?>
