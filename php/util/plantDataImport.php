<?php

// minimum temp (degrees F) for USDA plant hardiness zones in NM
// source: http://planthardiness.ars.usda.gov/
$usdaHardinessZones = [
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
	"9b" => 25.0

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

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

// iterate over PlantsForAFuture data and add to Plant table.
function insertPlantsForAFuture(\PDO $pdo){

// get line-by-line with pdo object.
	// TODO - currently only getting 1 record to test.
	$query = "SELECT `Latin name`, `Common name`, `Habit`, `Height`, `Width`, `Hardyness`, `FrostTender`, `Moisture`, `Edible uses`, `Uses notes`, `Cultivation details`, `Propagation 1`, `Author`, `Botanical references` FROM PlantsForAFuture LIMIT 1";
	$statement = $pdo->prepare($query);
	$statement = $pdo->execute();

	// get data from PDO object
	try {
		$plant = null;
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$row = $statement->fetch();

		if($row !== false) {
			$plantName = $row["Common name"];
			$latinName = $row["Latin name"];
			$plantVariety = null;
			$plantType = $row["Habit"];
			$plantDescription = $row["Edible uses"].$row["Uses notes"].$row["Cultivation details"].$row["Propagation 1"].$row["Author"].$row["Botanical references"];
			$plantSpread = $row["Width"];

// get min temps -
// if hardiness data available get from there

// if plant is "frost tender" then set to 32F (esp. if this is higher than hardiness zone temp

// if nothing specified, set to 32F


// plant description - take from plant uses, uses notes, cultivation details, propagation, author, references
		}
	} catch (\PDOException $pdoe){
		throw(new \PDOException($pdoe->getMessage(), 0, $pdoe));

	}
}

// iterate over NMSU Vegetable Data and add to Plant table (remember to check if an entry already exists for a given Plant Name.
function insertNMSUPlantData(){


}

// Add herb data?
