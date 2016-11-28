<?php
/**
 * Created by PhpStorm.
 * User: Growify
 * Date: 11/28/2016
 * Time: 12:20 AM
 */


require_once "autoloader.php";
require_once "/lib/xsrf.php";
require_once "/etc/apache2/capstone-mysql/encrypted-config.php";

use Edu\Cnm\Growify\Garden;


// Check the session status. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
// Here we create a new stdClass named $reply. A stdClass is basically an empty bucket that we can use to store things in.
// We will use this object named $reply to store the results of the call to our API. The status 200 line adds a state variable to $reply called status and initializes it with the integer 200 (success code). The proceeding line adds a state variable to $reply called data. This is where the result of the API call will be stored. We will also update $reply->message as we proceed through the API.


try {
	//grab the mySQL DataBase connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/tweet.ini");



	//determines which HTTP Method needs to be processed and stores the result in $method.
	$method = array_key_exists("HTTP_x_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//stores the Primary Key for the GET, DELETE, and PUT methods in $id. This key will come in the URL sent by the front end. If no key is present, $id will remain empty. Note that the input is filtered.
	$gardenProfileId = filter_input(INPUT_GET, "gardenProfileId", FILTER_VALIDATE_INT);



	//Here we check and make sure that we have the Primary Key for the DELETE and PUT requests. If the request is a PUT or DELETE and no key is present in $id, An Exception is thrown.
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $gardenProfileId < 0)) {
		throw(new InvalidArgumentException("Garden Profile Id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific tweet or all tweets and update reply
		if(empty($gardenProfileId) === false) {
			$garden = Garden::getGardensByGardenProfileId($pdo, $gardenProfileId);
			if($garden !== null) {
				$reply->data = $tweet;
			}
		}else{
			$gardens = Garden::getAllGardens($pdo);
			if($gardens !== null) {
				$reply->data = $gardens;
			}
		}
	} else if($method === "PUT" || $method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//make sure tweet content is available (required field)
		if(empty($requestObject->gardenProfileId) === true) {
			throw(new \InvalidArgumentException ("No Garden Profile Id", 405));
		}

		// make sure tweet date is accurate (optional field)
		if(empty($requestObject->gardenDatePlanted) === true){
			$requestObject->gardenDatePlanted = new \DateTime();
		}

		//  make sure profileId is available
		if(empty($requestObject->gardenPlantId) === true) {
			throw(new \InvalidArgumentException ("No Garden Plant ID.", 405));
		}

		//perform the actual put or post
		if($method === "PUT") {

			//TO DO: FINISH SOLUTION TO PUT*******************************************

			// retrieve the tweet to update
			$garden = Garden::getGardensByGardenProfileId($pdo, $gardenProfileId);
			if($garden === null) {
				throw(new RuntimeException("Garden(s) does not exist", 404));
			}

			/* update all attributes
			$tweet->setTweetDate($requestObject->tweetDate);
			$tweet->setTweetContent($requestObject->tweetContent);
			$tweet->update($pdo);

			// update reply
			$reply->message = "Tweet updated OK";
			*/
		} else if($method === "POST") {

			// create new tweet and insert into the database
			$garden = new Garden($requestObject->gardenProfileId, $requestObject->gardenDatePlanted, $requestObject->gardenPlantId);
			$garden->insert($pdo);

			// update reply
			$reply->message = "Garden created OK";
		}

	} else if($method === "DELETE") {
		verifyXsrf();
		//FINISH THIS ***********************************************************
		/**
		$gardens = Garden::getGardensByGardenProfileId($pdo, $gardenProfileId);
		if($gardens === null) {
			throw(new RuntimeException("Garden does not exist", 404));
		}

		// delete tweet
		$gardens->delete($pdo);

		// update reply
		$reply->message = "Garden(s) deleted OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
	*/
	}

	// update reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);

?>