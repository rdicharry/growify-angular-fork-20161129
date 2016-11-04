<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Profile, Plant};

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
	 * @var Profile profile
	 */
	protected $profile = null;

	/**
	 * The plant that is in the garden entry -
	 * this is to obtain a foreign key relation.
	 * @var Plant plant2;
	 */
	protected $plant1 = null;

	/**
	 * For tests that need a second valid plant.
	 * @var Plant plant2;
	 */
	protected $plant2 = null;

	/*
	 * the date this plant was planted in this garden
	 * @var DateTime $VALID_PLANTING_DATE
	 */
	protected $VALID_PLANTING_DATE = null/*TODO add valid test data*/;

	/**
	 * For tests that require a second valid planting date
	 * @var DateTime $VALID_PLANTING_DATE2
	 */
	protected $VALID_PLANTING_DATE2 = null/*TODO add valid test data*/;


	public final function setUp(){
		// run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Garden
		$this->profile = new Profile(/*TODO add params*/);
		$this->profile->insert($this->getPDO());

		// create and insert a Plant to go into the garden
		$this->plant1 = new Plant(/*TODO add params */);
		$this->plant1->insert($this->getPDO());

		// create and insert a second Plant to go into the garden
		// for tests that need two plants
		$this->plant2 = new Plant(/*TODO add params */);
		$this->plant2->insert($this->getPDO());

	}

	/**
	 * test inserting a valid garden and verify the data from mySQL matches.
	 */
	public function testInsertValidGarden(){
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("garden");

		//create a new Garden and insert into mySQL
		$garden = new Garden($this->profile->getProfileUserId(), $this->VALID_PLANTING_DATE, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// grab data from mySQL and check that fields match
		// TODO how to best attain adequate test coverage - could return multiple rows??
		$pdoGarden = Garden::getGardenByProfileUserId($this->getPDO, $this->profile->getProfileUserId());
		$this->assertEquals( $this->getConnection()->getRowCount("garden"), $numRows+1);
		$this->assertEquals($pdoGarden->getGardenUserId(),$this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant1->getPlantId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->VALID_PLANTING_DATE);

	}

	/**
	 * attempt to insert a garden entry that already exists
	 * e.g. all three fields duplicates of existing DB data
	 * @expectedException PDOException
	 */
	public function testInsertInvalidGarden(){

	}

	/**
	 * insert a garden entry, edit it and update in DB.
	 */
	public function testUpdateValidGardenPlantId(){
		// store numRows to compare later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert it into mySQL
		$garden = new Garden($this->profile->getProfileUserId(), $this->VALID_PLANTING_DATE, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// edit garden & update in mySQL
		$garden->setGardenPlantId($this->plant2->getPlantId());
		$garden->update($this->getPDO());

		// grab the data from mySQL and enforce fields match expected
		$pdoGarden = Garden::getGardenByUserId($this->getPDO(), $garden->getGardenId());
		$this->assertEquals( $this->getConnection()->getRowCount("garden"), $numRows+1);
		$this->assertEquals($pdoGarden->getGardenUserId(),$this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant2->getPlantId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->VALID_PLANTING_DATE);
	}

	/**
	 *
	 */
	public function testUpdateValidGardenDate(){
		// store numRows to compare later
		$numRows = $this->getConnection()->getRowCount("garden");

		// create a new Garden and insert it into mySQL
		$garden = new Garden($this->profile->getProfileUserId(), $this->VALID_PLANTING_DATE, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// edit garden & update in mySQL
		$garden->setGardenPlantId($this->VALID_PLANTING_DATE2);
		$garden->update($this->getPDO());

		// grab the data from mySQL and enforce fields match expected
		$pdoGarden = Garden::getGardenByUserId($this->getPDO(), $garden->getGardenId());
		$this->assertEquals( $this->getConnection()->getRowCount("garden"), $numRows+1);
		$this->assertEquals($pdoGarden->getGardenUserId(),$this->profile->getProfileId());
		$this->assertEquals($pdoGarden->getGardenPlantId(), $this->plant1->getPlantId());
		$this->assertEquals($pdoGarden->getGardenDatePlanted(), $this->VALID_PLANTING_DATE2);

	}

	/**
	 * attempt to update a garden that does not exist
	 * @expectedException PDOException
	 */
	public function testUpdateInvalidGarden(){

	}

	/**
	 * test creating a Garden and deleting it
	 */
	public function testDeleteValidGarden(){
		// save number of rows to compare later
		$numRows = $this->getConnection->getRowCount("garden");

		// create a new Garden and insert it into the DB
		$garden = new Garden($this->profile->getProfileUserId(), $this->VALID_PLANTING_DATE, $this->plant1->getPlantId());
		$garden->insert($this->getPDO());

		// delete Garden from mySQL
		$this->assertEquals($this->getConnection()->getRowCount("garden"),$numRows+1);
		$garden->delete($this->getPDO());

		// grab data from mySQL and enforce Garden does not exist
		$pdoGarden = Garden::getGardenByPlantId($this->getPDO(), $garden->getGardenPlantId());
		$this->assertNull($pdoGarden);
		$this->assertEquals($this->getConnection()->getRowCount("garden"), $numRows+1);
	}

	/**
	 * attempt to delete a GardenEntry that does not exist
	 * @expectedException PDOException
	 */
	public function testDeleteInvalidGarden() {
		// create a Garden and try to delete it without actually inserting it
		$garden = new Garden($this->profile->getProfileUserId(), $this->VALID_PLANTING_DATE, $this->plant1->getPlantId());
		$garden->delete($this->getPDO());
	}

	/**
	 * eliminate - this is the same as testInsertValidGarden()
	 * do not anticipate referencing gardens by plantID or by date planted
	 */
	/*
	public function testGetValidGardenByUserId(){

	}*/

	/**
	 * retrieve a list of all gardens
	 */
	public function testGetAllValidGardens(){

	}
}