<?php

use Edu\Cnm\Growify\Plant;

/**
 * Import data from NMSU planting dates tables into plantArea mySQL table.
 * Convert NMSU planting dates into USDA Hardiness Zones for ease of use.
 *
 * data source: // source http://aces.nmsu.edu/pubs/_circulars/CR457B.pdf
 *
 * This needs to be run **AFTER** importing data into Plant table
 * using the "plantDataImport.php" script so we can cross-reference the plantId number.
 */

$nmsuArea1ToUSDAHardiness = ["7b", "8a", "8b", "9a", "9b" ];
$nmsuArea2ToUSDAHardiness = ["6b", "7a"];
$nmsuArea3ToUSDAHardiness = ["4a", "4b", "5a", "5b", "6a"];

$months = [
	"JAN" => 1,
	"FEB" => 2,
	"MARCH" => 3,
	"APRIL" => 4,
	"MAY" => 5,
	"JUNE" => 6,
	"JULY" => 7,
	"AUG" => 8,
	"SEPT" => 9,
	"OCT" => 10,
	"NOV" => 11,
	"DEC" => 12
];

require_once "/etc/apache2/capstone-mysql/encrypted-config.php";
require_once(dirname(__DIR__) . "/classes/autoload.php");


$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

function importPlantingDates(\PDO $pdo){
	global $nmsuArea1ToUSDAHardiness;
	global $nmsuArea2ToUSDAHardiness;
	global $nmsuArea3ToUSDAHardiness;

	// for each plant table entry (plant variety) there are three growing zones
	// for each growing zone there are several USDA Hardiness zones - need to create a table entry for each one
	// for each growing zone date range, need to parse and convert the dates

	if(($handle = fopen("NMSUVegetableDataCSV.csv", "r")) !== FALSE) {
		while(($dataCSV = fgetcsv($handle, 0, ",", "\"")) !== FALSE) { // set length to zero for unlimited line length php > 5.1

			$plantName = $dataCSV[0];

			$plantVariety = $dataCSV[1];

			// get plant ID from Plant table via PDO
			$query = "SELECT plantId FROM plant WHERE plantName = :plantName AND plantVariety = :plantVariety";
			$statement = $pdo->prepare($query);
			$parameters = ["plantName" => $plantName, "plantVariety" => $plantVariety];
			$statement->execute($parameters);
			$statement->setFetchMode(\PDO::FETCH_ASSOC);

			$row = $statement->fetch();
			if($row === false){
				throw(new \PDOException("did not find Plant entry for plantName: ".$plantName." and plantVariety: ".$plantVariety));
			}
			$plantId = $row["plantId"];
			echo $plantName."<br/>";

			// parse and unwrap planting dates
			$plantArea1Dates = parseAndUnwrapDates($dataCSV[3]);
			$plantArea2Dates = parseAndUnwrapDates($dataCSV[4]);
			$plantArea3Dates = parseAndUnwrapDates($dataCSV[5]);
			echo $dataCSV[3]." ".$plantArea1Dates[0]." ".$plantArea1Dates[1]." ".$plantArea1Dates[2]." ".$plantArea1Dates[3]."<br/>";
			echo $dataCSV[4]." ".$plantArea2Dates[0]." ".$plantArea2Dates[1]." ".$plantArea2Dates[2]." ".$plantArea2Dates[3]."<br/>";
			echo $dataCSV[5]." ".$plantArea3Dates[0]." ".$plantArea3Dates[1]." ".$plantArea3Dates[2]." ".$plantArea3Dates[3]."<br/>";


			for($i=0; $i<count($nmsuArea1ToUSDAHardiness); $i++){
				$usdaArea = $nmsuArea1ToUSDAHardiness[$i];
				echo $usdaArea." ";
				$plantArea = new PlantArea(null, $plantId, $plantArea1Dates["startDate"], $plantArea1Dates["endDate"], $plantArea1Dates["startMonth"], $plantArea1Dates["endMonth"], $usdaArea);
				$plantArea->insert($pdo);
			}
			echo "<br/>";
			for($i=0; $i<count($nmsuArea2ToUSDAHardiness); $i++){
				echo $usdaArea." ";
				$usdaArea = $nmsuArea2ToUSDAHardiness[$i];
				$plantArea = new PlantArea(null, $plantId, $plantArea2Dates["startDate"], $plantArea2Dates["endDate"], $plantArea2Dates["startMonth"], $plantArea2Dates["endMonth"], $usdaArea);
				$plantArea->insert($pdo);

			}
			echo "<br/>";

			for($i=0; $i<count($nmsuArea3ToUSDAHardiness); $i++){
				echo $usdaArea." ";
				$usdaArea = $nmsuArea3ToUSDAHardiness[$i];
				$plantArea = new PlantArea(null, $plantId, $plantArea3Dates["startDate"], $plantArea3Dates["endDate"], $plantArea3Dates["startMonth"], $plantArea3Dates["endMonth"], $usdaArea);
				$plantArea->insert($pdo);

			}
			echo "<br/>";


		} // end while data CSV
	}// end if handle
}// end function

/**
 * Take a string representing a date range separated by a hyphen
 * and return an array of the start and end months and days
 * @param string $dateRange
 * @return array and associative array containing ["startDate", "startMonth", "endDate", "endMonth"]
 */
function parseAndUnwrapDates(string $dateRange){

	global $months;
	//$dateRange = trim($dateRange);
	$dateStrings = explode("—", $dateRange);
	$startString = $dateStrings[0];
	$endString = $dateStrings[1];
	$startString = trim($startString);
	$endString = trim($endString);
	$startMonthDay = explode(" ", $startString);
	$endMonthDay = explode(" ", $endString);

	$startMonth = $months[strtoupper($startMonthDay[0])];
	//$startDay = $startMonthDay[1];

	$endMonth = $months[strtoupper($endMonthDay[0])];
	//$endDay = $endMonthDay[0];


	$dates = ["startDate"=> $startMonthDay[1],
	"startMonth"=> $startMonth,
	"endDate"=> $endMonthDay[1],
	"endMonth"=> $endMonth];

	return $dates;

}