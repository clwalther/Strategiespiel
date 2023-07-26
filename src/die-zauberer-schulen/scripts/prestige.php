<?php

//include "global.php"; //TODO Jobarray Global machen

class PrestigeDistributer {

    private $prestigeDistributionArray;
    private $prestigeDistributionArraySize;
    private $jobarray;

    function __construct() {
        global $database;

        $this->database = $database;
        $this->database->connect();
        $this->prestigeDistributionArray = [300, 200, 100];
        $this->prestigeDistributionArraySize = count($this->prestigeDistributionArray);
        $this->jobarray = ["Medimagier", "Auror", "Ministeriumsbeamter", "Drachenwärter",
            "Magiezoologe", "Zauberstabschreinermeister", "Quidditchprofi"];
    }

    /**
     * Führt die Prestige-Verteilung für alle Jobs durch.
     */
    public function distributePrestigeOfAllJobs() {
        //echo var_dump($this->database->select("LABOUR_TABLE", ["*"])[2]["group_id"]);
        foreach ($this->jobarray as $job) {
            echo "Job: $job<br>";
            $columns = ["group_id", $job];
            $influenceArray = $this->database->select("LABOUR_TABLE", $columns);
            $sortedGroupIds = $this->getPlacementArrayByInfluence($influenceArray);
            $this->distributePrestige($sortedGroupIds);
        }
        echo "Prestige-Verteilung abgeschlossen.";
    }


    /**
     * Diese Funktion sortiert ein Array basierend auf den Influence-Werten der Keys und gibt ein neues Array zurück,
     * das die Keys in absteigender Reihenfolge ihrer Influence-Werte enthält.
     *
     * @param array $influenceArray Ein assoziatives Array mit den Keys als Schlüssel und den Influence-Werten als Werte.
     * @return array Ein neues Array, das die Keys des ursprünglichen Arrays enthält, sortiert nach der Höhe der Influence-Werte.
     *               Keys mit einem Influence-Wert von 0 werden nicht in das sortierte Array aufgenommen.
     */
    private function getPlacementArrayByInfluence(array $influenceArray): array {
        // Zuerst erstellen wir ein neues Array, das die Keys und ihre Influence-Werte enthält
        $influenceData = array();
        foreach ($influenceArray as $key => $influence) {
            // Nur Keys mit einem Influence-Wert größer als 0 hinzufügen
            if ($influence > 0) {
                $influenceData[$key] = $influence;
            }
        }

        // Das Array nach den Influence-Werten absteigend sortieren
        arsort($influenceData);

        // Das sortierte Array der Keys extrahieren
        $sortedKeys = array_keys($influenceData);
        return $sortedKeys;
    }

    /**
     * Diese Funktion verteilt das Prestige anhand der Influence-Werte der Gruppen eines Jobs.
     * @param array $sortedGroupIds Ein Array mit den Gruppen-IDs, sortiert nach der Höhe ihrer Influence-Werte.
     */
    private function distributePrestige($sortedGroupIds) {
        $groupsWithInfluence = count($sortedGroupIds);
        $iterations = min($groupsWithInfluence, $this->prestigeDistributionArraySize);
        $prestigeArray = $this->database->select("LABOUR_TABLE", ["group_id", "prestige"]);
        for ($i = 0; $i < $numIterations; $i++) {
            $groupId = $sortedGroupIds[$i];
            $prestigeToAdd = $this->prestigeDistributionArray[$i];
            addPrestige($groupId, $prestigeToAdd);
        }
    }

    private function setPrestige(int $groupId, int $prestige) {
        $groupIdContition = ["group_id", "=", $groupId];
        $this->database->update(LABOUR_TABLE, ["prestige" => $prestige], $groupIdContition);
    }

    private function addPrestige(int $groupId, int $prestigeToAdd) {
        $groupIdContition = ["group_id", "=", $groupId];
        $databaseReturn = $this->database->select_where("LABOUR_TABLE", ["group_id", "prestige"], $groupIdContition);
        $currentPrestige = $result[0]['prestige'];
        $newPrestige = $currentPrestige + $prestigeToAdd;
        $this->database->update(LABOUR_TABLE, ["prestige" => $newPrestige], $groupIdContition);
        echo "Gruppe ID: $groupId, Prestige: $prestige<br>";
    }

}

?>
