<?php

use Edu\Cnm\Growify\Plant;

// minimum temp (degrees F) for USDA plant hardiness zones in NM
// source: http://planthardiness.ars.usda.gov/
 $usdaHardinessZones = [
 	"1a" => -60.0,
	"1b" => -55.0,
	"2a" => -50.0,
	"2b" => -45.0,
	"3a" => -40.0,
	"3b" => -35.0,
	"4a" => -30.0,
	"4b" => -25.0,
	"5a" => -20.0,
	"5b" => -15.0,
	"6a" => -10.0,
	"6b" => -5.0,
	"7a" => 0.0,
	"7b" => 5.0,
	"8a" => 10.0,
	"8b" => 15.0,
	"9a" => 20.0,
	"9b" => 25.0,
	"10a" => 30,
	"10b" => 35,
	"11a" => 40,
	"11b" => 45,
	"12a" => 50,
	"12b" => 55,
	"13a" => 60,
	"13b" => 65
];

// cross-reference USDA plant hardiness (min winter temp) to NMSU planting areas
// source http://aces.nmsu.edu/pubs/_circulars/CR457B.pdf

 $usdaHardinessToNMSUAreas = [
	"4a" => 3,
	"4b" => 3,
	"5a" => 3,
	"5b" => 3,
	"6a" => 3,
	"6b" => 2,
	"7a" => 2,
	"7b" => 1,
	"8a" => 1,
	"8b" => 1,
	"9a" => 1,
	"9b" => 1
];

require_once "/etc/apache2/capstone-mysql/encrypted-config.php";
require_once(dirname(__DIR__) . "/classes/autoload.php");


$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

// works best if you insert plants for afuture first.
function insertPlantData($pdo){
	insertPlantsForAFuture($pdo);
	//insertNMSUPlantData($pdo);
}

// iterate over PlantsForAFuture data and add to Plant table.
function insertPlantsForAFuture(\PDO $pdo){

	global $usdaHardinessZones;

// get line-by-line with pdo object.
	$query = "SELECT `Latin name`, `Common name`, `Habit`, `Height`, `Width`, `Hardyness`, `FrostTender`, `Moisture`, `Edible uses`, `Uses notes`, `Cultivation details`, `Propagation 1`, `Author`, `Botanical references` FROM PlantsForAFuture ";
	$statement = $pdo->prepare($query);
	$statement->execute();

	// get data from PDO object
	try {
		$plant = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);


		while(($row = $statement->fetch()) !== false ){}
			if($row !== false) {
				$plantName = $row["Common name"];
				$latinName = $row["Latin name"];
				$plantVariety = null;
				$plantType = $row["Habit"];

				// plant description - take from plant uses, uses notes, cultivation details, propagation, author, references
				$plantDescription = $row["Edible uses"] . $row["Uses notes"] . $row["Cultivation details"] . $row["Propagation 1"] . $row["Author"] . $row["Botanical references"];
				$plantSpread = $row["Width"]; // meters - convert to feet
				$plantHeight = $row["Height"]; // meters - convert to feet
				$plantDaysToHarvest = null; // not provided in this table

				// get min temps -
				// if hardiness data available get from there
				$plantMinTemp = 32; // default min temp is 32 F
				$hardiness = null;
				if($row["Hardyness"] !== null) {
					$hardiness = intval($row["Hardyness"]);
					if($hardiness > 0) {

						$plantMinTemp = $usdaHardinessZones[$hardiness . "b"];
					}
				}

				// if plant is "frost tender" then set to 32F (esp. if this is higher than hardiness zone temp
				if($row["FrostTender"] === "Y") {
					if($plantMinTemp < 32) {
						$plantMinTemp = 32;
					}
				}
				// (if nothing specified, default to 32F)

				$plantMaxTemp = null; // we dont have ANY data for this. :P

				$plantSoilMoisture = $row["Moisture"];

				$plant = new Plant(null, $plantName, $latinName, $plantVariety, $plantType, $plantDescription, $plantSpread, $plantHeight, $plantDaysToHarvest, $plantMinTemp, $plantMaxTemp, $plantSoilMoisture);
				$plant->insert($pdo);
		}

			/*echo $plant->getPlantId()."<br>";
			echo $plant->getPlantName()."<br>";
			echo $plant->getPlantLatinName()."<br>";
			echo $plant->getPlantVariety()."<br>";
			echo $plant->getPlantType()."<br>";
			echo $plant->getPlantDescription()."<br>";
			echo $plant->getPlantSpread()."<br>";
			echo $plant->getPlantHeight()."<br>";
			echo $plant->getPlantDaysToHarvest()."<br>";
			echo $plant->getPlantMinTemp()."<br>";
			echo $plant->getPlantMaxTemp()."<br>";
			echo $plant->getPlantSoilMoisture()."<br>";*/



	} catch (\PDOException $pdoe){
		throw(new \PDOException($pdoe->getMessage(), 0, $pdoe));

	}
}

// iterate over NMSU Vegetable Data and add to Plant table (remember to check if an entry already exists for a given Plant Name.
function insertNMSUPlantData(){

	// first step - see if this plant already has an entry
	// query on plantName

	// if the entry is there, update it

	// if the entry is not there, insert it.

}

// Add herb data?

insertPlantData($pdo);
