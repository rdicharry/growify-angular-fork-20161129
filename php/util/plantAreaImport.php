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


			// parse and unwrap planting dates ToDO
			$plantArea1Dates = $dataCSV[3]; // TODO strip whitespace at beg & end (trim?)
			$plantArea1Dates = $dataCSV[4]; // TODO
			$plantArea1Dates = $dataCSV[5];

			for($i=0; $i<count($nmsuArea1ToUSDAHardiness); $i++){
				$usdaArea = $nmsuArea1ToUSDAHardiness[$i];
				$plantArea = new PlantArea(); // TODO insert
			}
			for($i=0; $i<count($nmsuArea2ToUSDAHardiness); $i++){
				$usdaArea = $nmsuArea2ToUSDAHardiness[$i];
				$plantArea = new PlantArea(); // TODO insert
			}
			for($i=0; $i<count($nmsuArea3ToUSDAHardiness); $i++){
				$usdaArea = $nmsuArea3ToUSDAHardiness[$i];
				$plantArea = new PlantArea(); // TODO insert
			}

		} // end while data CSV
	}// end if handle
}