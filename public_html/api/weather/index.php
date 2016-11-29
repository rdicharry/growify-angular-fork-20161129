<?php
require_once(dirname(__DIR__, 3)."/php/classes/Weather.php");
require_once(dirname(__DIR__, 3)."/php/lib/xsrf.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
// start the session and create a xsrf token
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {

	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");
// determines which HTTP method needs to be processed
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//we are likely sending either a zip code via http request
//  grab data from front end (location - zip code) for specific request
	$zipcode = filter_input(INPUT_GET, "zipcode", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$current = filter_input(INPUT_GET, "current", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($zipcode) === true ){
		throw( new InvalidArgumentException("zip code location cannot be empty or non-numeric"));
	}



// this comes from "get" request
	if($method === "GET"){
		// set XSRF cookie
		setXsrfCookie("/");
		if($current === "true") {
			$weather = Weather::getCurrentWeatherByZipcode($pdo, $zipcode);
			if($weather !== null) {
				$reply->data = $weather;
			}
		} else {
			$weather = Weather::getWeekForecastWeatherByZipcode($pdo, $zipcode);
			if($weather !== null){
				$reply->data = $weather;
			}
		}
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	}
} catch (\Exception $e) {
	$reply->status = $e->getCode();
	$reply->message = $e->getMessage();
	throw new Exception($e->getMessage());
} catch (TypeError $te){
	$reply->status = $te->getCode();
	$reply->message = $te->getMessage();
}
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}
echo json_encode($reply);