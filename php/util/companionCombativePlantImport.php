<?php

use Edu\Cnm\Growify\CompanionPlant;
use Edu\Cnm\Growify\CombativePlant;
use Edu\Cnm\Growify\Plant;


/**
 * import data about pairs of companion and combative plants.
 * source: http://www.ufseeds.com/Vegetable-Companion-Planting-Chart.html
 */


require_once "/etc/apache2/capstone-mysql/encrypted-config.php";
require_once(dirname(__DIR__) . "/classes/autoload.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

importCompanionPlants($pdo);

function importCompanionPlants(\PDO  $pdo) {

	if(($handle = fopen("companion-plants.csv", "r")) !== FALSE) {
		while(($dataCSV = fgetcsv($handle, 0, ",", "\"")) !== FALSE) { // set length to zero for unlimited line length php > 5.1

			$plant1Name = trim($dataCSV[0]);
			$plant2Name = trim($dataCSV[1]);

			echo $plant1Name.", ".$plant2Name."<br/>";
			// get plant id for ALL varieties!
			$plant1PlantEntries = Plant::getPlantByPlantName($pdo, $plant1Name);
			$plant2PlantEntries = Plant::getPlantByPlantName($pdo, $plant2Name);

			// table entries are the outer product of all these plants! eek!
			for($i = 0; $i < count($plant1PlantEntries); $i++){
				for($j = 0; $j <count($plant2PlantEntries); $j++){
					echo $plant1PlantEntries[$i]->getPlantId().", ".$plant2PlantEntries[$j]->getPlantId()."<br/>";
					try {
					$companionEntry = new CompanionPlant($plant1PlantEntries[$i]->getPlantId(), $plant2PlantEntries[$j]->getPlantId());

						$companionEntry->insert($pdo);
					} catch(\PDOException $pe){
						echo($pe->getMessage()."<br/>");
					}
				}
			}



		}
	}
}