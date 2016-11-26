<?php

use Edu\Cnm\Growify\Plant;
use Edu\Cnm\Growify\PlantArea;


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

importPlantingDates($pdo);

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

			// arrays to store dates if multiple ranges exist
			$plantArea1Dates = [];
			$plantArea2Dates = [];
			$plantArea3Dates = [];

			echo $dataCSV[3] ."<br/>";
			if($pos = strpos($dataCSV[3], ";")){
				// if there is a semicolon, it indicates more than one date range
				$plantArea1DateRange1 = substr($dataCSV[3],0,$pos);
				$plantArea1DateRange2 = substr($dataCSV[3], $pos+1);
				array_push($plantArea1Dates, parseAndUnwrapDates($plantArea1DateRange1));
				array_push($plantArea1Dates, parseAndUnwrapDates($plantArea1DateRange2));
			} else {
				array_push($plantArea1Dates, parseAndUnwrapDates($dataCSV[3]));
			}


			echo $dataCSV[4] ."<br/>";
			if($pos = strpos($dataCSV[4], ";")){
				// if there is a semicolon, it indicates more than one date range
				$plantArea2DateRange1 = substr($dataCSV[4],0,$pos);
				$plantArea2DateRange2 = substr($dataCSV[4], $pos+1);
				array_push($plantArea2Dates, parseAndUnwrapDates($plantArea2DateRange1));
				array_push($plantArea2Dates, parseAndUnwrapDates($plantArea2DateRange2));
			} else {
				array_push($plantArea2Dates, parseAndUnwrapDates($dataCSV[4]));
			}

			echo $dataCSV[5] ."<br/>";
			if($pos = strpos($dataCSV[5], ";")){
				// if there is a semicolon, it indicates more than one date range
				$plantArea3DateRange1 = substr($dataCSV[5],0,$pos);
				$plantArea3DateRange2 = substr($dataCSV[5], $pos+1);
				array_push($plantArea2Dates, parseAndUnwrapDates($plantArea3DateRange1));
				array_push($plantArea2Dates, parseAndUnwrapDates($plantArea3DateRange2));
			} else {
				array_push($plantArea3Dates, parseAndUnwrapDates($dataCSV[5]));
			}

			for($i=0; $i<count($nmsuArea1ToUSDAHardiness); $i++) {
				$usdaArea = $nmsuArea1ToUSDAHardiness[$i];
				echo $usdaArea . " ";

				if($plantArea1Dates[0]===null){
					continue; // no date range given for this plant and area;
				}

				for($j = 0; $j < count($plantArea1Dates); $j++) {
					echo $plantArea1Dates[$j]["startDate"]." ".$plantArea1Dates[$j]["startMonth"]." through ".$plantArea1Dates[$j]["endDate"]." ".$plantArea1Dates[$j]["endMonth"]."<br/>";

					$plantArea = new PlantArea(null, $plantId, $plantArea1Dates[$j]["startDate"], $plantArea1Dates[$j]["endDate"], $plantArea1Dates[$j]["startMonth"], $plantArea1Dates[$j]["endMonth"], $usdaArea);
					$plantArea->insert($pdo);
				}
			}
			echo "<br/>";
			for($i=0; $i<count($nmsuArea2ToUSDAHardiness); $i++){

				$usdaArea = $nmsuArea2ToUSDAHardiness[$i];
				echo $usdaArea." ";

				if($plantArea2Dates[0]===null){
					continue; // no date range given for this plant and area;
				}

				for($j = 0; $j < count($plantArea2Dates); $j++) {
					echo $plantArea2Dates[$j]["startDate"]." ".$plantArea2Dates[$j]["startMonth"]." through ".$plantArea2Dates[$j]["endDate"]." ".$plantArea2Dates[$j]["endMonth"]."<br/>";

					$plantArea = new PlantArea(null, $plantId, $plantArea2Dates[$j]["startDate"], $plantArea2Dates[$j]["endDate"], $plantArea2Dates[$j]["startMonth"], $plantArea2Dates[$j]["endMonth"], $usdaArea);
					$plantArea->insert($pdo);
				}
			}
			echo "<br/>";

			for($i=0; $i<count($nmsuArea3ToUSDAHardiness); $i++){

				$usdaArea = $nmsuArea3ToUSDAHardiness[$i];
				echo $usdaArea." ";

				if($plantArea3Dates[0]===null){
					continue; // no date range given for this plant and area;
				}

				for($j = 0; $j < count($plantArea3Dates); $j++) {
					echo $plantArea3Dates[$j]["startDate"]." ".$plantArea3Dates[$j]["startMonth"]." through ".$plantArea3Dates[$j]["endDate"]." ".$plantArea3Dates[$j]["endMonth"]."<br/>";

					$plantArea = new PlantArea(null, $plantId, $plantArea3Dates[$j]["startDate"], $plantArea3Dates[$j]["endDate"], $plantArea3Dates[$j]["startMonth"], $plantArea3Dates[$j]["endMonth"], $usdaArea);
					$plantArea->insert($pdo);
				}
			}
			echo "<br/>";


		} // end while data CSV
	}// end if handle
}// end function

/**
 * Take a string representing a date range separated by a hyphen
 * and return an array of the start and end months and days
 * @param string $dateRange
 * @return array|null and associative array containing ["startDate", "startMonth", "endDate", "endMonth"] or null if the date range does not exist
 */
function parseAndUnwrapDates(string $dateRange){

	global $months;
	//$dateRange = trim($dateRange);
	$dateStrings = explode("—", $dateRange);
	$startString = $dateStrings[0];
	$endString = $dateStrings[1];
	$startString = trim($startString);
	$endString = trim($endString);
	if($startString === "" || $endString=== "" ){
		// date range not fully specified, possibly no valid
		// date range given for this planting area and plant
		return null;
	}
	$startMonthDay = explode(" ", $startString);
	$endMonthDay = explode(" ", $endString);

	$startMonth = $months[strtoupper($startMonthDay[0])];

	if(is_numeric($endMonthDay[0])){
		// if start and end date fall in the same month,
		// the name of the month is not lsited in the csv file,
		// so grab the date and end month is same as start month.
		$endDay = $endMonthDay[0];
		$endMonth = $startMonth;
	} else {
		$endMonth = $months[strtoupper($endMonthDay[0])];
		$endDay = $endMonthDay[1];
	}


	$dates = ["startDate"=> $startMonthDay[1],
	"startMonth"=> $startMonth,
	"endDate"=> $endDay,
	"endMonth"=> $endMonth];

	return $dates;

}