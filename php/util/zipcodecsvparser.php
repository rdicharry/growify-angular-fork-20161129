<?php
/**
 * Created by PhpStorm.
 * Users: Daniel Eaton, Rebecca, Greg, Ana
 * Date: 10/29/2016
 * Time: 9:34 PM

 * Author: Growify Team
 */
namespace Edu\Cnm\Growify;
use Edu\Cnm\Growify\ZipCode;

require_once(dirname(__DIR__) . "/classes/autoload.php");
require_once("Setup.php");

function csvParse() {
	$file = file_get_contents('zip_code_database.csv');
	$zonesFile = file_get_contents('zones-and-towns-utf8.csv');

	//INITIALIZE TOWNZONEARRAY
	$zoneCsv = str_getcsv($zonesFile);
	$townZoneArray = array();
	for($x = 0; $x < count($zoneCsv)-1; $x += 2) {
		$areavar = $zoneCsv[$x];
		$zonevar = $zoneCsv[$x + 1];
		$townZoneArray[] = [$areavar, $zonevar];
	}

//INITIALIZE ZIPCODE ARRAY
	$zipCodeAreaArray = array();
	$csv = str_getcsv($file);
	for($x = 0; $x < (count($csv) - 3); $x += 3) {
		if(substr($csv[$x], 0, 2) === "NM") {
			$zipcode = substr($csv[$x - 3], 2);
			$city = $csv[$x - 2];
			$additionalarea = ($csv[$x - 1]);
			$zipCodeAreaArray[] = [$zipcode, $city, $additionalarea];
		}
	}
//MATCH ZIPCODES TO AREAS AND INITIALIZE TO ZIPCODE
	$zipCode = array();
	for($x = 0; $x < count($zipCodeAreaArray); $x++) {
		for($y = 0; $y < count($townZoneArray); $y++) {
			if(trim(filter_var($townZoneArray[$y][0], FILTER_SANITIZE_STRING)) === trim(filter_var($zipCodeAreaArray[$x][1], FILTER_SANITIZE_STRING))) {
				$zipCode[] = [$zipCodeAreaArray[$x][0], $townZoneArray[$y][1]];
				break;
			}else{
				//echo 'Additional Array: '.$zipCodeAreaArray[$x][2]."  townZoneArray: ".$townZoneArray[$y][0]. "<br>";
				$areas = explode(",",$zipCodeAreaArray[$x][2]);
				for($z = 0; $z < count($areas); $z++){
					//echo   "AREA:".$areas[$z]." TOWNZONE" .$townZoneArray[$y][0]. "<br>";

					if(filter_var(trim($areas[$z]), FILTER_SANITIZE_STRING) === filter_var(trim($townZoneArray[$y][0]),FILTER_SANITIZE_STRING)){
						if(!($zipCodeAreaArray[$x][0] === $zipCodeAreaArray[$x-1][0])){
							$zipCode[] = [$zipCodeAreaArray[$x][0],$areas[$z]];
							echo "match: " ."  AREA  ".$areas[$z]." TOWNZONE  " .$townZoneArray[$y][0]. ' ZIPCODE: ' .$zipCodeAreaArray[$x][0] ." MAINAREA ". $zipCodeAreaArray[$x][1] ."<br>";
						}
					}

				}
			}
		}
	}
	//$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");
	for($x = 0; $x < count($zipCode); $x++) {
		//$zipCodeObject
				//$zipCodeObject->insert($pdo);
	}
	echo count($townZoneArray)."<br>";
	echo count($zipCodeAreaArray). "<br>";
	echo count($zipCode);
}
csvParse();


