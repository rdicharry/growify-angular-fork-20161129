<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\Plant;
use Edu\Cnm\Growify\CompanionPlant;


//grab the project test parameters
require_once("GrowifyTest.php");

//grab the class under scrutiny
require_once(dirname(__DIR__) ."/classes/autoload.php");
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
	 * @var Plant companionPlant1Id
	 **/
	protected $companionPlant1 = null;
	/**
	 * Foreign key relationship to Plant
	 * @var Plant companionPlant2Id
	 **/
	protected $companionPlant2 = null;

	/**
	 * Create objects necessary for test (dependencies)
	 **/
	public final function setUp() {
		//run default setUp() method
		parent::setUp();

		// create and insert a Plant to go into the gardent
		$this->companionPlant1 = new Plant(null, "truffula tree", "this is a latin name", "green", "Primary food source for Brown Barbaloots", "tree", 5, 100, 5, 32, 99, "d" );
		$this->companionPlant1->insert($this->getPDO());

		// create and insert a second Plant to go into the garden
		// for tests that need two plants
		$this->companionPlant2 = new Plant(null, "Audrey", "this is a latin name too", "custom", "companion", "vine",  1000, 100, 10, 32, 99, "h");
		$this->companionPlant2->insert($this->getPDO());
	}

	/**
	 * insert a companion plant entry and verify that the mySQL entry data matches.
	 * Note: we should be able to get companion plant entries regardless of whether the plantId is found in the first or second entry.
	 * Note: this should return an array of values (possibly more than one entry for a given companion plant.
	 *
	 **/
	public function testInsertValidCompanionPlantEntry() {
		// store number of rows for later
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new companion plant entry and insert it into mySQL
		$companionPlant = new companionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO());

		//grab date from mySQL and enforce fields match
		// e.g. all returned entries have the expected ID as either 1st or 2nd field.
		$results = CompanionPlant::getAllCompanionPlantsByPlantId($this->getPDO(), $companionPlant->getCompanionPlant1Id());

		//first check array parameters
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant", $results);

		//get result from the array and validate it
		$pdoCompanionPlant = $results[0]; //only one entry in this test
		$this->assertTrue(($pdoCompanionPlant->getCompanionPlant1Id() === $this->companionPlant1->getPlantId()) ||
			($pdoCompanionPlant->getCompanionPlant2Id() === $this->companionPlant1->getPlantId()));

	}

	/**
	 * Ensure the existsCompanionPlantEntry() works as expected - returns true if the companion plant has been entered.
	 **/

	public function testExistsCompanionPlantEntry() {
		// create a new companion plant entry and insert it into mySQL
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO());

		$this->assertTrue(CompanionPlant::existsCompanionPlantEntry($this->getPDO(), $this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId() ));
	}

	/**
	 * Ensure the existsCompanionPlantEntry() works as expected - returns false if the companion plant has not been entered.
	 **/
	public function testsExistsCompanionPlantEntryNoEntry() {
		// ask if the entry exists for an entry that shouldn't

		$this->assertFalse(CompanionPlant::existsCompanionPlantEntry($this->getPDO(), $this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId()));
	}

	/**
	 * do we get expected behavior when attempting to create duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 **/
	public function testInsertDuplicateValidCompanionPlantEntry() {

		$testCompanionPlant1 = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$testCompanionPlant1->insert($this->getPDO());
		try {
		$testCompanionPlant2 = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$testCompanionPlant2->insert($this->getPDO());
	} catch(\PDOException $pdoException) {
$this->assertTrue(true); // caught expected exception
}

// check that no rows are affected.
$results = CompanionPlant::getAllCompanionPlantsByPlantId($this->getPDO(), $this->companionPlant1->getPlantId());
$this->assertCount(1, $results);
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
		$testCompanionPlant1 = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$testCompanionPlant1->insert($this->getPDO());
		$testCompanionPlant2 = new CompanionPlant($this->companionPlant2->getPlantId(), $this->companionPlant1->getPlantId());
		$testCompanionPlant2->insert($this->getPDO());
	}

	/**
	 * test deleting a valid plant entry
	 **/
	public function testDeleteValidCompanionPlantEntry(){
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new CompanionPlant and insert into mySQL
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO());

		// delete that companionPlant
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$companionPlant->delete($this->getPDO());

		// get data from mySQL and enforce the entry was deleted

		$pdoCompanionPlants = CompanionPlant::getAllCompanionPlantsByPlantId ($this->getPDO(), $this->companionPlant1->getPlantId());
		$this->assertCount(0, $pdoCompanionPlants);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("companionPlant"));
	}


	/**
	 * Insert companion plant object into mySQL as companionPlant1Id, companionPlant2Id, check that doing delete on companionPlant2Id, companionPlant1Id removes that object.
	 *
	 **/

	public function testDeleteValidCompanionPlantEntryOrderInsensitive() {
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new CompanionPlant and insert into mySQL
		$companionPlant1 = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant1->insert($this->getPDO());

		// delete a companionPlant created with reverse indices
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$companionPlant2 = new CompanionPlant($this->companionPlant2->getPlantId(), $this->companionPlant1->getPlantId());
		$companionPlant2->delete($this->getPDO());

		// get data from mySQL and enforce the entry was deleted.
		$pdoCompanionPlant = CompanionPlant::getAllCompanionPlantsByPlantId ($this->getPDO(), $this->companionPlant1->getPlantId());
		//$this->assertEmpty($pdoCompanionPlant);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount ("companionPlant"));

	}

	/**
	 * test deleting a companion plant entry that does not exist
	 * @expectedException \PDOException
	 **/
	public function testDeleteInvalidCompanionPlantEntry(){
		// create a CompanionPlant and try to delete without actually inserting it
		$companionPlant = new CompanionPlant(GrowifyTest::INVALID_KEY,$this->companionPlant2->getPlantId());
		$companionPlant->delete($this->getPDO());
	}

	/**
	 * test ability to retrieve the CompanionPlant record by the second Plant entry (companionPlant2)
	 **/
	public function testGetValidCompanionPlantEntryByPlantId(){

		//we shouldn't know what order the plants will be inside the DB so need to test against either one (two plant id's)
		//a query for a particular companion plant should return all valid plants
		//more than one Plant entry to test against e.g. should be able to retriever the entries with the plantId as either companionPlant1Id or companionPlant2Id
		//count number or rows and save for later
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new Companion Plant and insert it into mySQL
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO());

		// grab the data and enforce fields match expectations
		$results = CompanionPlant::getAllCompanionPlantsByPlantId($this->getPDO(), $this->companionPlant2->getPlantId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant", $results);

		// get result from the array and validate it
		$pdoCompanionPlant = $results[0];
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant1Id(), $this->companionPlant1->getPlantId());
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant2Id(), $this->companionPlant2->getPlantId());
	}

	/**
	 * attempt to get a plant for which no entry exists
	 **/
	public function testGetInvalidCompanionPlantEntryByPlantId() {

		// get a CompanionPlant entry by searching for a plant that does not exist

		$companionPlants = CompanionPlant::getAllCompanionPlantsByPlantId($this->getPDO(), $this->companionPlant2->getPlantId());
		$this->assertCount(0, $companionPlants);
	}
	/**
	 * test getting a list of ALL companion plants
	 **/
	public function testGetAllValidCompanionPlants(){
		// count number of rows and save for later
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new Companion Plant and insert it into mySQL
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO());

		// grab the data and enforce fields match expectations
		$results = CompanionPlant::getAllCompanionPlants($this->getPDO());
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant", $results);

		//get result from the array and validate it
		$pdoCompanionPlant =$results[0];
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant1Id(),$this->companionPlant1->getPlantId());
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant2Id(),$this->companionPlant2->getPlantId());
	}

}