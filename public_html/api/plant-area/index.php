<?php

require_once "autoloader.php";
require_once "/lib/xsrf.php";
require_once "/etc/apache2/capstone-mysql/encrypted-config.php";

use Edu\Cnm\growify;

/**
 * api for plant area class
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

// Handle GET request
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie("/");

//sanitize input
		$plantAreaId = filter_input(INPUT_GET, "plantAreaId", FILTER_VALIDATE_INT);
		$plantAreaPlantId = filter_input(INPUT_GET, "plantAreaPlantId", FILTER_VALIDATE_INT);


