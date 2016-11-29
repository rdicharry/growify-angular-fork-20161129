<?php
/**
 * Created by PhpStorm.
 * User: Growify
 * Date: 11/24/2016
 * Time: 10:33 PM
 */
//namespace Edu\Cnm\Growify;
require_once dirname(__DIR__,3)."/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once "/etc/apache2/capstone-mysql/encrypted-config.php";
use Edu\Cnm\Growify\ZipCode;


// Check the session status. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new \stdClass();
$reply->status = 200;
$reply->data = null;


try {
	//grab the mySQL DataBase connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/growify.ini");


	//determines which HTTP Method needs to be processed and stores the result in $method.
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//stores the Primary Key for the GET, DELETE, and PUT methods in $id. This key will come in the URL sent by the front end. If no key is present, $id will remain empty. Note that the input is filtered.
	$id = filter_input(INPUT_GET, "id");


	//Here we check and make sure that we have the Primary Key for the DELETE and PUT requests. If the request is a PUT or DELETE and no key is present in $id, An Exception is thrown.
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("ZipCode cannot be empty", 405));
	}
// we determine if we have a GET request. If so, we then process the request.

// Here, we determine if the reques received is a GET request
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		// handle GET requests - if id is present, that tweet is present, that tweet is returned, otherwise all tweets are returned
		// Here, we determine if a Key was sent in the URL by checking $id. If so, we pull the requested Tweet by Tweet ID from the DataBase and store it in $tweet.
		if(empty($id) === false) {
			$zipCode = ZipCode::getZipCodeByZipCodeCode($pdo, $id);
			echo "ZipCodeCode: ".$zipCode->getZipCodeCode();
			if($zipCode !== null) {
				$reply->data = $zipCode->getZipCodeArea();
				// Here, we store the retreived Tweet in the $reply->data state variable.
			}
		}else {
			throw new \InvalidArgumentException("ZipCode Cannot Be Empty",405);
		}
	}
		// If there is nothing in $id, and it is a GET request, then we simply return all tweets. We store all the tweets in the $tweets varable, and then store them in the $reply->data state variable.

	}catch(\Exception $e){
	$reply->status = $e->getCode();
	$reply->message = $e->getMessage();
	}catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
	}

header("Content-type: application/json");
 //sets up the response header.
/*
if($reply->data === null) {
	unset($reply->data);
}
*/

echo json_encode($reply);
// finally - JSON encodes the $reply object and sends it back to the front end.

