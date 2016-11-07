<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant, CompanionPlant};


//grab the project test parameters
require_once("GrowifyTest.php");

//grab the class under scrutiny
require_once("CompanionPlantTest.php");

/**
 * Full PHPUnit test for the CompanionPlant class
 *
 * This is a complete PHPUnit test for the CompanionPlant class because all mySQL/PDO enabled methods are tested for valid and invalid inputs.
 *
 * @see CompanionPlant
 * @author Ana Vela <avela7@cnm.edu>
 *
 */
class CompanionPlantTest extends GrowifyTest {

	/**
	 * Foreign key relationship to Plant
	 * @var Plant companionPlant1
	 **/
	protected $companionPlant1Id = null;
	/**
	 * Foreign key relationship to Plant
	 * @var Plant companionPlant2
	 **/
	protected $companionPlant2Id = null;

	/**
	 * Create objects necessary for test (dependencies)
	 **/
	public final function setUp() {
		//run default setUp() method
		parent::setUp();

		// create and insert a Plant1Id
		$this->companionPlant1Id = new Plant(/*TODO add parameters */);
		$this->companionPlant1Id->insert($this->getPDO());

		// create and insert a Plant2Id
		$this->companionPlant2Id = new Plant(/*TODO add parameters */);
		$this->companionPlant2Id->insert($this->getPDO());
	}
 	/**
	 * insert a companion plant entry and verify that the mySQL entry data matches.
	 * Note: we should be able to get companion plant entries regardless of whether the plantId is found in the first or second entry.
	 * Note: this should return an array of values (possibly more than one entry for a given companion plant.
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertValidCompanionPlantEntry() {
		// store number of rows for later
		$numRows = $this->getConnection()->getRowCount ("companionPlant");

		// create a new companion plant entry and insert it into mySQL
		$companionPlant = new companionPlant($this->companionPlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$companionPlant->insert($this->getPDO());

		//grab date from mySQL and enforce fields match
		// e.g. all returned entries have the expected ID as either 1st or 2nd field.
		$results = CompanionPlant::getCompanionPlantsByPlantId($this->getPDO (), $companionPlant->getCompanionPlant1Id());
		//first check array parameters
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount ("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant");

		//get result from the array and validate it
		$pdoCompanionPlant = $results[0]; //only one entry in this test
		$this->assertTrue(($pdoCompanionPlant->getCompanionPlant1Id () === $this->companionPlant1Id->getPlantId() )||
			($pdoCompanionPlant->geetCompanionPlant2Id() === $this->companionPlant2Id->getPlantId()));

	}

	/**
	 * do we get expected behavior when attempting to create duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertDuplicateValidCompanionPlantEntry() {

		$testCompanionPlant1Id = new CompanionPlant ($this->combativePlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$testCompanionPlant1Id->insert($this->getPDO());
		$testCompanionPlant2Id = new CompanionPlant ($this->companionPlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$testCompanionPlant2Id->insert($this->getPDO());
	}


	/**
	 * attempt to insert a companion plant entry that already exists (with order of plantId's reversed)
	 *
	 * e.g. (companionPlant1Id = 1, companionPlant2Id = 2) and
	 *      (companionPlant1Id = 2, companionPlant2Id = 1)
	 * are really the same entry, and we neither require nor do we want to have BOTH
	 * @expectedException \PDOException
	 **/
	public function testInsertDuplicateValidCombativePlantEntrySwapIds() {
		$testCompanionPlant1Id = new CompanionPlant ($this->companionPlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$testCompanionPlant1Id->insert($this->getPDO());
		$testCompanionPlant2Id = new CompanionPlant($this->companionPlant2Id->getPlantId(), $this->companionPlant1Id->getPlantId());
		$testCompanionPlant2Id->insert($this->getPDO());
	}




/**
 * test updating a companion plant entry that does not exist
 * @expectedException PDOException
 **/
	public function testUpdateInvalidCompanionPlantEntry() {

	}

	/**
	 * test deleting a valid plant entry
	 **/
	public function testDeletValidCompanionPlantEntry(){
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new CompanionPlant and insert into mySQL
		$companionPlant = new CompanionPlant($this->CompanionPlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$companionPlant->insert($this->getPDO());

		// delete that companionPlant
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$companionPlant->delete($this->getPDO());

		// get data from mySQL and enforce the entry was deleted
		$pdoCompanionPlant = CompanionPlant::getCompanionPlantByBothPlantIds($this->companionPlant1Id->getPlantId(), $this->companionPlant2Id->getPlantId());
		$this->assertNull($pdoCompanionPlant);
		$this->assertEquals($numRows, $this->getConnecion()->getRowCount("companionPlant"));

	}

	/**
	 * test deleting a companion plant entry that does not exist
	 * @expectecException PDOException
	 **/
	public function testDeleteValidCompanionPlantEntry(){

	}
	/**
	 * test deleting a companion plant entry that does not exist
	 * @expectedException PDOException
	 **/
	public function testInvalidCompanionPlantEntry() {

	}

	/**
	 * do we get expected data?
	 **/
	public function testGetValidCompanionPlantEntryByPlantId(){

		// we shouldn't know what order the plants will be inside the DB
		// so need to test against either one (two plant id's)

		// a query for a particular companion plant should return all
		// valid plants that it is paired with - so we might need to use
		// more than one Plant entry to test against
	}
	/**
	 * attempt to get a plant for which no entry exists
	 **/
	public function testGetInvalidCompanionPlantEntryByPlantId(){

		// we shouldn't know what order th plants will be inside the DB
		// so need to test against either one (two plant id's)
	}
	/**
	 * test getting a list of ALL companion plants
	 **/
	public function testGetAllValidCompanionPlants(){

	}
}