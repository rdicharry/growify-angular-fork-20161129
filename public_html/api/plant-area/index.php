<?php

require_once "autoloader.php";
require_once "/lib/xsrf.php";
require_once "/etc/apache2/capstone-mysql/encrypted-config.php";

use Edu\Cnm\growify\PlantArea;

/**
 * API for PlantArea class
 *
 * @author Ana Vela avela7@cnm.edu>
 **/

//start the session and create a xsrf token
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the mySQL DataBase connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");


	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];


//sanitize input
	$plantAreaId = filter_input(INPUT_GET, "plantAreaId", FILTER_VALIDATE_INT);
	$plantAreaPlantId = filter_input(INPUT_GET, "plantAreaPlantId", FILTER_VALIDATE_INT);
	$plantAreaNumber = filter_input(INPUT_GET, "plantAreaNumber", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// ensure $plantAreaId is valid
	if($plantAreaId < 0) {
		throw(new InvalidArgumentException ("plant area Id cannot be negative", 405));
	}

// Handle GET request
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

	// If id is present, get the plant area for that id
	if(empty($plantAreaId) === false) {
		$plantArea = PlantArea::getPlantAreaByPlantAreaId($pdo, $plantAreaId);
		if($plantArea !== null) {
			$reply->data = $plantArea;
			//Here, we store the retreived PlantArea in the $reply->data state variable.
		}

	} else {
		$plantAreas = PlantArea::getAllPlantAreas($pdo);
		if($plantAreas !== null) {
			$reply->data = $plantAreas;
		}
	}
} else {
		throw(new InvalidArgumentException("Invalid HTTP method request"));
	}

// if plant id and plant area number are present get the plant area for that combination -- (need help with this)


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





