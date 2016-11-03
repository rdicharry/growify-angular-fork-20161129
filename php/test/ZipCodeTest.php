<?php

namespace Edu\Cnm\growify\php\Test;

use Edu\Cnm\growify\Test\GrowifyTest;
use Edu\Cnm\growify\php\ZipCode;

// grab the project test parameters
require_once("GrowifyTest.php.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");

/**
* Full PHPUnit test for the Tweet class
*
* This is a complete PHPUnit test of the Tweet class. It is complete because *ALL* mySQL/PDO enabled methods
* are tested for both invalid and valid inputs.
*
* @see Tweet
* @author Growify
**/
class TweetTest extends GrowifyTest {
/**
* content of the Tweet
* @var string $VALID_ZipCodeCode
**/
protected $VALID_ZIPCODECODE = "87002";
/**
* content of the updated Tweet
* @var string $VALID_TWEETCONTENT2
**/
protected $VALID_ZIPCODEAREA = "5b";

/**
* content of a valid zipCodeArea
 * @var string $VALID_ZIPCODEAREA
**/
protected $VALID_ZIPCODEAREA2  = "7a";

/**
 * content of an invalid zipCodeCode
 * @var string $INVALID_ZIPCODECODE
 */
protected $INVALID_ZIPCODECODE = "04200";

public final function setUp() {
// run the default setUp() method first
parent::setUp();

}

/**
* test inserting a valid ZipCode and verify that the actual mySQL data matches
**/
public function testInsertValidZipCode() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("zipCode");

// create a new Tweet and insert to into mySQL
$zipCode = new ZipCode($this->VALID_ZIPCODECODE,$this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdozipCode = ZipCode::getZipCodeByZipCodeCode($this->getPDO(), $zipCode->getZipCodeCode());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$this->assertEquals($pdozipCode->zipCodeCode(), $this->VALID_ZIPCODECODE);
$this->assertEquals($pdozipCode->getzipCodeArea(), $this->VALID_ZIPCODEAREA);
}

/**
* test inserting a ZipCode that already exists
*
* @expectedException PDOException
**/
public function testInsertInvalidZipCode() {
// create a Tweet with a non null tweet id and watch it fail
$zipCode = new ZipCode($this->INVALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());
}

/**
* test inserting a Tweet, editing it, and then updating it
**/
public function testUpdateValidZipCode() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("tweet");

// create a new Tweet and insert to into mySQL
$zipCode = new ZipCode($this->VALID_ZIPCODECODE,$this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// edit the Tweet and update it in mySQL
$zipCode->setCodeArea($this->VALID_ZIPCODEAREA2);
$zipCode->update($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoZipCode = ZipCode::getZipCodebyZipCodeCode($this->getPDO(), $zipCode->getZipCodeArea());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$this->assertEquals($pdoZipCode->getZipCodeCode(), $this->$zipCode->getZipCodeCode());
$this->assertEquals($pdoZipCode->getZipCodeArea(), $this->VALID_ZIPCODEAREA2);
}

/**
* test updating a Tweet that does not exist
*
* @expectedException PDOException
**/
public function testUpdateInvalidTweet() {
// create a Tweet, try to update it without actually updating it and watch it fail
$zipCode = new Tweet($this->INVALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->update($this->getPDO());
}

/**
* test creating a Tweet and then deleting it
**/
public function testDeleteValidTweet() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("tweet");

// create a new Tweet and insert to into mySQL
$zipCode = new ZipCode($this->VALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// delete the Tweet from mySQL
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$zipCode->delete($this->getPDO());

// grab the data from mySQL and enforce the Tweet does not exist
$pdoTweet = ZipCode::getZipCodeByZipCodeCode($this->getPDO(), $zipCode->getZipCodeCode);
$this->assertNull($pdoTweet);
$this->assertEquals($numRows, $this->getConnection()->getRowCount("tweet"));
}

/**
* test deleting a Tweet that does not exist
*
* @expectedException PDOException
**/
public function testDeleteInvalidTweet() {
// create a Tweet and try to delete it without actually inserting it
$tweet = new Tweet(null, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
$tweet->delete($this->getPDO());
}

/**
* test grabbing a Tweet by tweet content
**/
public function testGetValidTweetByTweetContent() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("tweet");

// create a new Tweet and insert to into mySQL
$tweet = new Tweet(null, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
$tweet->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$results = Tweet::getTweetByTweetContent($this->getPDO(), $tweet->getTweetContent());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
$this->assertCount(1, $results);
$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Tweet", $results);

// grab the result from the array and validate it
$pdoTweet = $results[0];
$this->assertEquals($pdoTweet->getProfileId(), $this->profile->getProfileId());
$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT);
$this->assertEquals($pdoTweet->getTweetDate(), $this->VALID_TWEETDATE);
}

/**
* test grabbing a Tweet by content that does not exist
**/
public function testGetInvalidTweetByTweetContent() {
// grab a tweet by searching for content that does not exist
$tweet = Tweet::getTweetByTweetContent($this->getPDO(), "you will find nothing");
$this->assertCount(0, $tweet);
}

/**
* test grabbing all Tweets
**/
public function testGetAllValidTweets() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("tweet");

// create a new Tweet and insert to into mySQL
$tweet = new Tweet(null, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
$tweet->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$results = Tweet::getAllTweets($this->getPDO());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
$this->assertCount(1, $results);
$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Dmcdonald21\\DataDesign\\Tweet", $results);

// grab the result from the array and validate it
$pdoTweet = $results[0];
$this->assertEquals($pdoTweet->getProfileId(), $this->profile->getProfileId());
$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT);
$this->assertEquals($pdoTweet->getTweetDate(), $this->VALID_TWEETDATE);
}
}