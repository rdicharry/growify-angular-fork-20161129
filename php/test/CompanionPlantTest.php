<?php
namespace Edu\Cnm\Growify\Test;


use Edu\Cnm\Growify\CompanionPlant;
use Edu\Cnm\Growify\Plant;

//grab the project test parameters
require_once("GrowifyTest.php");

//grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
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

		// create and insert a Plant1
		$this->companionPlant1 = new Plant(null, "wildcarrot", "orangelike", "what bunnies eat", "longish", 7, 200, 55, 38, 89, "c");
		$this->companionPlant1->insert($this->getPDO());

		// create and insert a Plant2
		$this->companionPlant2 = new Plant(null, "minitomato", "smallest", "round and shiny", "fruity", 8, 10, 44, 45, 71, "t");
		$this->companionPlant2->insert($this->getPDO());
	}
 	/**
	 * insert a companion plant entry and verify that the mySQL entry data matches.
	 * Note: we should be able to get companion plant entries regardless of whether the plantId is found in the first or second entry.
	 * Note: this should return an array of values (possibly more than one entry for a given companion plant.
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertValidCompanionPlantEntry() {
		// store number of rows for later
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new companion plant entry and insert it into mySQL
		$companionPlant = new companionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
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
		$this->assertTrue(($pdoCompanionPlant->getCompanionPlant1Id () === $this->companionPlant1->getPlantId() )||
			($pdoCompanionPlant->getCompanionPlant2Id() === $this->companionPlant2->getPlantId()));

	}

	/**
	 * do we get expected behavior when attempting to create duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertDuplicateValidCompanionPlantEntry() {

		$testCompanionPlant1Id = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$testCompanionPlant1Id->insert($this->getPDO());
		$testCompanionPlant2Id = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
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
		$testCompanionPlant1Id = new CompanionPlant ($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$testCompanionPlant1Id->insert($this->getPDO());
		$testCompanionPlant2Id = new CompanionPlant($this->companionPlant2->getPlantId(), $this->companionPlant1->getPlantId());
		$testCompanionPlant2Id->insert($this->getPDO());
	}

	/**
	 * test deleting a valid plant entry
	 **/
	public function testDeletValidCompanionPlantEntry(){
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("companionPlant");

		// create a new CompanionPlant and insert into mySQL
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$companionPlant->insert($this->getPDO(null));

		// delete that companionPlant
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$companionPlant->delete($this->getPDO(null));

		// get data from mySQL and enforce the entry was deleted
		$pdoCompanionPlant = CompanionPlant::getCompanionPlantByBothPlantIds($this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId(5));
		$this->assertNull($pdoCompanionPlant);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("companionPlant"));
	}

	/**
	 * test deleting a companion plant entry that does not exist
	 * @expectecException PDOException
	 **/
	public function testDeleteValidCompanionPlantEntry(){
		// create a CompanionPlant and try to delete without actually inserting it
		$companionPlant = new CompanionPlant($this->companionPlant1->getPlantId(),$this->companionPlant2->getPlantId());
		$companionPlant->delete($this->getPDO());
	}

	/**
	 * test ability to retrieve the CompanionPlant record by the second Plant entry (companionPlant2)
	 **/
	public function testGetValidCombativePlantEntryByPlantId(){

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
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant");

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

		$companionPlant = CompanionPlant::getCompanionPlantByBothPlantIds($this->getPDO(), $this->companionPlant1->getPlantId(), $this->companionPlant2->getPlantId());
		$this->assertEquals(null, $companionPlant);
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
		$results = CompanionPlant::getAllCompanionPLants($this->PDO());
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("companionPlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CompanionPlant");

		//get result from the array and validate it
		$pdoCompanionPlant =$results[0];
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant1Id(),$this->companionPlant1->getPlantId());
		$this->assertEquals($pdoCompanionPlant->getCompanionPlant2Id(),$this->companionPlant2->getPlantId());
	}

}