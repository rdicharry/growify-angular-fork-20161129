<?php


require_once "autoloader.php";
require_once "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\Growify\Profile;

/**
 * API for the Profile class.
 * @author Greg Bloom
 */

// Check the session. If it is not active, start the session.
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

	//check which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$profileId = filter_input(INPUT_GET, "profileId", FILTER_VALIDATE_INT);

	// check if $profileId is valid
	if($profileId < 0){
		throw(new InvalidArgumentException("profile Id cannot be negative", 405));
	}

	// handle GET request
	if($method === "GET"){
		// set XSRF Cookie
		setXsrfCookie();

		//get profile or all profiles
		if(empty($profileId)===false){
			$profile = Profile::getProfileByProfileId($pdo, $profileId);
			if($profile !== null){
				$reply->data = $profile;
			}
		} else {
			$profiles = Profile::getAllProfiles($pdo);
			if($profiles !== null){
				$reply->data = $profiles;
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