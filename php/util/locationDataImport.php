<?php

use Edu\Cnm\Growify\Location;

/**
 * import data for converting from zip code to latitude longitude
 * useful for accessing weather API
 * source: http://www.census.gov/geo/maps-data/data/gazetteer2010.html
 */


require_once "/etc/apache2/capstone-mysql/encrypted-config.php";
require_once(dirname(__DIR__) . "/classes/autoload.php");

$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

importLocationData($pdo);

function importLocationData(\PDO  $pdo) {

	if(($handle = fopen("NMzipcodearea-tolatlong", "r")) !== FALSE) {
		while(($dataTSV = fgetcsv($handle, 0, "\t", "\"")) !== FALSE) { // set length to zero for unlimited line length php > 5.1
			$zipCode = trim($dataTSV[0]);
			$latitude = floatval(trim($dataTSV[7]));
			$longitude = floatval(trim($dataTSV[8]));

			echo $zipCode.", ".$latitude." ".$longitude."<br/>";
			$location = new Location($zipCode, $latitude, $longitude);
			$location->insert($pdo);

		}
	}
}