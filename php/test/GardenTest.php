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
	 * @var Plant plant;
	 */
	protected $plant = null;

	/*
	 * the date this plant was planted in this garden
	 * @var DateTime $VALID_PLANTING_DATE
	 */
	protected $VALID_PLANTING_DATE = null/*TODO add valid test data*/;

	public final function setUp(){
		// run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Garden
		$this->profile = new Profile(/*TODO add params*/);
		$this->profile->insert($this->getPDO());

		// create and insert a Plant to go into the garden
		$this->plant = new Plant(/*TODO add params */);
		$this->plant->insert($this->getPDO());


	}

	/**
	 * test inserting a valid garden and verify the data from mySQL matches.
	 */
	public function testInsertValidGarden(){

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
	public function testUpdateValidGarden(){

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