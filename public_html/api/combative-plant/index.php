<?php


require_once "autoloader.php";
require_once "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Growify\CombativePlant;

/**
 * API for the CombativePlant class.
 * @author Rebecca Dicharry
 */

// verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try{

	// get mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$plantId = filter_input(INPUT_GET, "combativePlantId", FILTER_VALIDATE_INT);

	// ensure $plantId is valid
	if($plantId < 0){
		throw(new InvalidArgumentException("plant Id cannot be negative", 405));
	}

	// handle GET request
	if($method === "GET"){
		// set XSRF Cookie
		setXsrfCookie();

		//get combative plant or all combative plants
		if(empty($plantId)===false){
			$combativePlant = CombativePlant::getCombativePlantsByPlantId($pdo, $plantId);
			if($combativePlant !== null){
				$reply->data = $combativePlant;
			}
		} else {
			$combativePlants = CombativePlant::getAllCombativePlants($pdo);
			if($combativePlants !== null){
				$reply->data = $combativePlants;
			}
		}
	} else {
		throw(new InvalidArgumentException("Invalid HTTP method request"));
	}

} catch (Exception $exception){
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError){
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}

echo json_encode($reply);