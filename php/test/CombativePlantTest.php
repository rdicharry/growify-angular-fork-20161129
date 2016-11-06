<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant, CombativePlant};

// grab the project test parameters
require_once("GrowifyTest.php");

// grab the class under scrutiny
require_once("CombativePlantTest.php");

/**
 * Full PHPUnit test for the CombativePlant class.
 *
 * This is a complete PHPUnit test fo the CombativePlant class because
 * all mySQL/PDO enabled methods are tested for valid AND invalid inputs.
 *
 * @see CombativePlant
 * @author Rebecca Dicharry <rdicharry@cnm.edu>
 *
 */
class CombativePlantTest extends GrowifyTest {

	/**
	 * Foreign key relationship to Plant.
	 * @var Plant combativePlant1Id
	 */
	protected $combativePlant1 = null;
	/**
	 * Foreign key relationship to Plant.
	 * @var Plant combativePlant2Id
	 */
	protected $combativePlant2 = null;

	/**
	 * Create objects necessary for test (dependencies).
	 */
	public final function setUp(){
		// run default setUp() method
		parent::setUp();

		// create and insert a Plant1
		$this->combativePlant1 = new Plant(/*TODO add parameters */);
		$this->combativePlant1->insert($this->getPDO());

		//create and insert a Plant2
		$this->combativePlant2 = new Plant(/*TODO add parameters*/);
		$this->combativePlant2->insert($this->getPDO());

	}

	/**
	 * insert a combative plant entry and verify that the
	 * mySQL entry data matches.
	 * Note: we should be able to get combative plant entries regardless of whether the plantId is found in the first or second entry.
	 * Note: this should return an array of values (possible more than one entry for a given combative plant.
	 *
	 * @expectedException PDOException
	 */
	public function testInsertValidCombativePlantEntry(){
		// store number of rows for later
		$numRows = $this->getConnection()->getRowCount("combativePlant");

		// create a new combative plant entry and insert it into mySQL
		$combativePlant = new CombativePlant($this->combativePlant1->getPlantId(), $this->combativePlant2->getPlantId());
		$combativePlant->insert($this->getPDO());

		// grab data from mySQL and enforce fields match
		// e.g. all returned entries have the expected ID
		// as either 1st or 2nd field.
		$results = CombativePlant::getCombativePlantsByPlantId($this->getPDO(), $combativePlant->getCombativePlant1Id());
		// first check array parameters
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("combativePlant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\CombativePlant");

		// get result from the array and validate it.
		$pdoCombativePlant = $results[0]; // only one entry in this test
		$this->assertTrue(($pdoCombativePlant->getCombativePlant1Id() === $this->combativePlant1->getPlantId() )|| ($pdoCombativePlant->getCombativePlant2Id() === $this->combativePlant1->getPlantId()));


	}

	/**
	 * do we get expected behavior when attempting to create a duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 * @expectedException \PDOException
	 */
	public function testInsertDuplicateValidCombativePlantEntry(){

		$testCombativePlant1 = new CombativePlant($this->combativePlant1->getPlantId(), $this->combativePlant2->getPlantId());
		$testCombativePlant1->insert($this->getPDO());
		$testCombativePlant2 = new CombativePlant($this->combativePlant1->getPlantId(), $this->combativePlant2->getPlantId());
		$testCombativePlant2->insert($this->getPDO());
	}

	/**
	 * attempt to insert a combative plant entry that already exists
	 * (with order of plantId's reversed)
	 *
	 * e.g. (CombativePlant1Id = 1, CombativePlant2Id = 2) and
	 *      (CombativePlant1Id = 2, CombativePlant1Id = 1)
	 * are really the same entry, and we neither require nor do we want to have BOTH
	 * @expectedException \PDOException
	 */
	public function testInsertDuplicateValidCombativePlantEntrySwapIds(){
		$testCombativePlant1 = new CombativePlant($this->combativePlant1->getPlantId(), $this->combativePlant2->getPlantId());
		$testCombativePlant1->insert($this->getPDO());
		$testCombativePlant2 = new CombativePlant($this->combativePlant2->getPlantId(), $this->combativePlant1->getPlantId());
		$testCombativePlant2->insert($this->getPDO());
	}

	/**
	 * test inserting an invalid plant entry (one that has an invalid (non-null) id)
	 */
	/* don't use this - it makes more sense to test attempt to
	 add duplicate entry - see testInsertDuplicateValidCombativePlantEntry()
	public function testInsertInvalidCombativePlantEntry(){

	}*/

	/**
	 * test updating a combative plant entry. - omit as we do not currently
	 * have a use case for updating a combative plant entry
	 */



	/**
	 * test deleting a valid plant entry
	 */
	public function testDeleteValidCombativePlantEntry(){
		// count the number of rows and save to compare
		$numRows = $this->getConnection()->getRowCount("combativePlant");

		// create a new CombativePlant and insert into mySQL
		$combativePlant = new CombativePlant($this->combativePlant1->getPlantId(), $this->combativePlant2->getPlantId());
		$combativePlant->insert($this->getPDO());

		// delete that combativePlant
		$this->assertEquals($numRows+1, $this->getConnection()->getRowCount("combativePlant"));
		$combativePlant->delete($this->getPDO());

		// get data from mySQL and enforce the entry was deleted.
		$pdoCombativePlant = CombativePlant::getCombativePlantByBothPlantIds(){

		}

	}

	/**
	 * Insert combative plant object into mySQL as combativePlant1Id, combativePlant2Id, check that doing delete on combativePlant2Id, combativePlant1Id removes that object.
	 */
	public function testDeleteValidCombativePlantEntryOrderInsensitive(){
		// create object
	}

	/**
	 * test deleting a Combative plant entry that does not exist
	 * @expectedException PDOException
	 */
	public function testDeleteInvalidCombativePlantEntry(){

	}

	/**
	 * Do we get expected data?
	 */
	public function testGetValidCombativePlantEntryByPlantId(){

		// we shouldn't know what order the plants will be inside the DB
		// so need to test against either one (two plant id's)

		// a query for a particular combative plant should return all
		// valid plants that it is paired with - so we might need to use
		// more than one Plant entry to test against. e.g. should be able to retrieve the entries with the plantId as either combativePlant1Id or combativePlant2Id

	}

	/**
	 * Attempt to get a plant for which no entry exists.
	 */
	public function testGetInvalidCombativePlantEntryByPlantId(){

		// we shouldn't know what order the plants will be inside the DB
		// so need to test against either one (two plant id's)

	}

	/**
	 * test getting a list of ALL combative plant entries
	 */
	public function testGetAllValidCombativePlants(){
		
	}
}