<?php

// include "global.php"; //TODO Jobarray Global machen

class InfluenceCalculator {

    private $jobIdSkillsIdArray;
    private $skillsMultiplikatorArray;

    function __construct() {
        global $database;

        $this->database = $database;

        $this->$jobSkillsIdArray = array(
            "Medimagier" => [1,6,2],
            "Auror" => [3,1,7],
            "Ministeriumsbeamter" => [4,2,6],
            "Drachenwärter" => [5,2,7],
            "Magiezoologe" => [5,6,7],
            "Zauberstabschreinermeister" => [6,4,2],
            "Quidditchprofi" => [7,1,4]
        );

        $this->$skillsMultiplikatorArray = [5, 3, 1]; //TODO Stellschraube

    }

    public function addGraduate(int $groupId, string $job, array $skills) {
        $influenceToAdd = getInfluence($job, $skills);
        addInfluence($groupId, $job, $influenceToAdd);
    }

    public function getInfluence(string $job, array $skills){
        $skillsIdArray = $this->$jobSkillsIdArray[$job];
        $influence = 0;
        for ($i = 0; $i < 3; $i++) {
            $skillsId = $skillsIdArray[$i];
            $skillPoints = $skills[$skillsId-1];
            $influenceToAdd = $influenceToAdd + $skillPoints * $this->$skillsMultiplikatorArray[$i];
        }
        return $influence;
    }

    private function setInfluence(int $groupId, string $job, int $influence) {
        $groupIdContition = ["group_id", "=", $groupId];
        $database->update(LABOUR_TABLE, [$job => $influence], $groupIdContition);
    }

    private function addInfluence(int $groupId, string $job, int $influenceToAdd) {
        $groupIdContition = ["group_id", "=", $groupId];
        $databaseReturn = $database->select_where("LABOUR_TABLE", ["group_id", $job], $groupIdContition);
        $currentInfluence = $result[0][$job];
        $newInfluence = $currentInfluence + $influenceToAdd;
        $database->update(LABOUR_TABLE, [$job => $newInfluence], $groupIdContition);
    }
}

?>
