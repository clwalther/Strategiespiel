<?php

class PrestigeDistributer
{
    function __construct() {
        global $database;
        global $utils;

        $this->database = $database;
        $this->utils = $utils;

        // aquires the file contents
        $file = file_get_contents(DATA_FILE_PATH);
        $this->file = json_decode($file, true);


        $this->prestigeDistributionArray = [30, 20, 10, 5, 3, 1]; //TODO Stellschraube
        $this->prestigeDistributionArraySize = count($this->prestigeDistributionArray);
    }

    /**
     * Führt die Prestige-Verteilung für alle Jobs durch.
     */
    public function distributePrestigeOfAllJobs() {
        foreach ($this->file["general"]["jobs"] as $job_name) {
            $influence_array = $this->utils->get_influence($job_name);
            $sortedGroupIds = $this->getPlacementArrayByInfluence($influence_array, $job_name);
            $this->distributePrestige($sortedGroupIds);
        }
    }


    /**
     * Diese Funktion sortiert ein Array basierend auf den Influence-Werten der Keys und gibt ein neues Array zurück,
     * das die Keys in absteigender Reihenfolge ihrer Influence-Werte enthält.
     *
     * @param array $influenceArray Ein assoziatives Array mit den Keys als Schlüssel und den Influence-Werten als Werte.
     * @return array Ein neues Array, das die Keys des ursprünglichen Arrays enthält, sortiert nach der Höhe der Influence-Werte.
     *               Keys mit einem Influence-Wert von 0 werden nicht in das sortierte Array aufgenommen.
     */
    private function getPlacementArrayByInfluence(array $influenceArray, string $job): array {
        // Zuerst erstellen wir ein neues Array, das die Keys und ihre Influence-Werte enthält
        $influenceData = array();
        foreach ($influenceArray as $groupId => $influence) {
            // Nur Keys mit einem Influence-Wert größer als 0 hinzufügen
            if ($influence > 0) {
                $influenceData[$groupId] = $influence;
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
        // $prestigeArray = $this->database->select("LABOUR", ["group_id", "prestige"]);

        for ($i = 0; $i < $iterations; $i++) {
            $groupId = $sortedGroupIds[$i];
            $prestigeToAdd = $this->prestigeDistributionArray[$i];
            $this->addPrestige($groupId, $prestigeToAdd);
        }
    }

    private function addPrestige(int $groupId, float $prestigeToAdd) {
        $groupIdContition = ["group_id" => $groupId];
        $databaseReturn = $this->database->select_where("LABOUR", ["group_id", "prestige"], $groupIdContition);
        $currentPrestige = $databaseReturn[0]['prestige'];
        $newPrestige = $currentPrestige + $prestigeToAdd;
        $this->database->update("LABOUR", ["prestige" => $newPrestige], $groupIdContition);
    }
}

?>
