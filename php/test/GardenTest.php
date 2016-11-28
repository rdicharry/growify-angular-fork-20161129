<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Profile, Plant, Garden, ZipCode};
use DateTime;

// grab the project test parameters
require_once("GrowifyTest.php");

//grab the class under scrutiny
require_once(dirname(__DIR__)."/classes/autoload.php");

/**
 * Full PHPUnit test for the Garden class
 *
 * Complete because all mySQL statements are tested for both valid
 * and invalid inputs.
 * @see GardenTest
 * @author Rebecca Dicharry <rdicharry@cnm.edu>
 *
 */
class GardenTest extends GrowifyTest {

	/**
	 * The profile that created the garden; this for the foreign key relation.
	 * @var \Profile profile
	 */
	protected $profile = null;

	/**
	 * The plant that is in the garden entry -
	 * this is to obtain a foreign key relation.
	 * @var \Plant plant2;
	 */
	protected $plant1 = null;

	/**
	 * For tests that need a second valid plant.
	 * @var \Plant plant2;
	 */
	protected $plant2 = null;

	/*
	 * the date this plant was planted in this garden
	 * @var \DateTime $validPlantingDate
	 */
	protected $validPlantingDate;

	/**
	 * For tests that require a second valid planting date
	 * @var \DateTime validPlantingDate2
	 */
	protected $validPlantingDate2;

	/**
	 * @var \ZipCode zipCode
	 */
	protected $zipCode;

	public final function setUp(){
		// run the default setUp() method first
		parent::setUp();

		$this->zipCode = new ZipCode("87120", "5b");
		$this->zipCode->insert($this->getPDO());

		//generate *test* hash & salt for profile

		$password = "acd123";
		$salt = bin2hex(random_bytes(32));
		$hash = hash_pbkdf2("sha512", $password, $salt, 262144);

		$activation = bin2hex(random_bytes(8));


		// create and insert a Profile to own the test Garden
		$this->profile = new Profile(null, "lorax1971", "the.lorax@oncelerco.com", $this->zipCode->getZipCodeCode(), $hash ,$salt, $activation);
		$this->profile->insert($this->getPDO());

		// create and insert a Plant to go into the gardent
		$this->plant1 = new Plant(null, "truffula tree", "this is a latin name", "green", "Primary food source for Brown Barbaloots", "tree", 5, 100, 5, 32, 99, "d" );
		$this->plant1->insert($this->getPDO());

		// create and insert a second Plant to go into the garden
		// for tests that need two plants
		$this->plant2 = new Plant(null, "Audrey", "this is a latin name too", "custom", "companion", "vine",  1000, 100, 10, 32, 99, "h");
		$this->plant2->insert($this->getPDO());

		$this->validPlantingDate = new DateTime("2016-03-04");

		$this->validPlantingDate2 = new DateTime("2016-02-16");

	}

	/**
	 * Test inserting a valid garden and verify the data from mySQL matches. Note that this also provides coverage for the getGardensByGardenProfileId() method.  Note also that at this time, we do not anticipate referencing gardens by plantID or by date planted
	 */
	public function testInsertValidGarden(){
		// store number of rows to compare for later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert int mySQL
		$garden = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// grab data from mySQL and enforce fields match expectations
		$results = Garden::getGardensByGardenProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("garden"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Garden", $results);

		// get the result from the array and validate it
		$pdoGarden = $results[0];
		$this->assertEquals($pdoGarden->getGardenProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->validPlantingDate);
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant1->getPlantId());

	}

	/**
	 * attempt to insert a garden entry that already exists
	 * e.g. all three fields duplicates of existing DB data
	 * @expectedException PDOException
	 */
	public function testInsertInvalidGarden(){
		$garden1 = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden1->insert($this->getPDO());

		$garden2 = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden2->insert($this->getPDO());
	}



	/**
	 * insert a garden entry, edit it and update in DB.
	 */
	public function testUpdateValidGardenPlantId(){
		// store numRows to compare later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert it into mySQL
		$garden = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// edit garden & update in mySQL
		$garden->setGardenPlantId($this->plant2->getPlantId());
		$garden->update($this->getPDO());

		// grab the data from mySQL and enforce fields match expected
		$pdoGardens = Garden::getGardensByGardenProfileId($this->getPDO(), $garden->getGardenProfileId());
		$this->assertEquals( $this->getConnection()->getRowCount("garden"), $numRows+1);
		$this->assertCount(1, $pdoGardens);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Garden", $pdoGardens);

		$pdoGarden = $pdoGardens[0];
		$this->assertEquals($pdoGarden->getGardenProfileId(),$this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant2->getPlantId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->validPlantingDate);
	}

	/**
	 *
	 */
	public function testUpdateValidGardenDate(){
		// store numRows to compare later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert it into mySQL
		$garden = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// edit garden & update in mySQL
		$garden->setGardenDatePlanted($this->validPlantingDate2);
		$garden->update($this->getPDO());

		// grab the data from mySQL and enforce fields match expected
		$pdoGardens = Garden::getGardensByGardenProfileId($this->getPDO(), $garden->getGardenProfileId());
		$this->assertEquals( $this->getConnection()->getRowCount("garden"), $numRows+1);
		$this->assertCount(1, $pdoGardens);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Garden", $pdoGardens);

		$pdoGarden = $pdoGardens[0];
		$this->assertEquals($pdoGarden->getGardenProfileId(),$this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant1->getPlantId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->validPlantingDate2);

	}

	/**
	 * attempt to update a garden that does not exist
	 * @expectedException PDOException
	 */
	public function testUpdateInvalidGarden(){

		$garden = new Garden($this->profile->getProfileUserId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->update($this->getPDO());

	}

	/**
	 * test creating a Garden and deleting it
	 */
	public function testDeleteValidGarden(){
		// save number of rows to compare later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert it into the DB
		$garden = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// delete Garden from mySQL
		$this->assertEquals($this->getConnection()->getRowCount("garden"),$numRows+1);
		$garden->delete($this->getPDO());

		// grab data from mySQL and enforce Garden does not exist
		$pdoGarden = Garden::getGardensByGardenProfileId($this->getPDO(), $garden->getGardenProfileId());
		$this->assertNull($pdoGarden);
		$this->assertEquals($this->getConnection()->getRowCount("garden"), $numRows);
	}

	/**
	 * attempt to delete a GardenEntry that does not exist
	 * @expectedException PDOException
	 */
	public function testDeleteInvalidGarden() {
		// create a Garden and try to delete it without actually inserting it
		$garden = new Garden($this->profile->getProfileUserId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->delete($this->getPDO());
	}




	/**
	 * test retrieving a list of all gardens
	 */
	public function testGetAllValidGardens(){

		// count the number of rows and save for later
		$numRows = $this->getconnection()->getRowCount("garden");

		//create a new Garden and insert int mySQL
		$garden = new Garden($this->profile->getProfileId(), $this->validPlantingDate, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// grab data from mySQL and enforce fields match our expectations.
		$results = Garden::getAllGardens($this->getPDO());
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("garden"));
		$this->assertCount(1,$results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Garden", $results);

		// get result from the array and validate it
		$pdoGarden = $results[0];
		$this->assertEquals($pdoGarden->getGardenProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->validPlantingDate);
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant1->getPlantId());
	}
}