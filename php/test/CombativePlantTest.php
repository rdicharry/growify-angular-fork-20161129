<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant};

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
	protected $combativePlant1Id = null;
	/**
	 * Foreign key relationship to Plant.
	 * @var Plant combativePlant2Id
	 */
	protected $combativePlant2Id = null;

	/**
	 * Create objects necessary for test (dependencies).
	 */
	public final function setUp(){
		// run default setUp() method
		parent::setUp();

		// create and insert a Plant1
		$this->combativePlant1Id = new Plant(/*TODO add parameters */);
		$this->combativePlant1Id->insert($this->getPDO());

		//create and insert a Plant2
		$this->combativePlant2Id = new Plant(/*TODO add parameters*/);
		$this->combativePlant2Id->insert($this->getPDO());

	}

	/**
	 * insert a combative plant entry and verify that the
	 * mySQL entry data matches.
	 * @expectedException PDOException
	 */
	public function testInsertValidCombativePlantEntry(){

	}

	/**
	 * do we get expected behavior when attempting to create a duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 * this includes reversing the fields
	 * e.g. (CombativePlant1Id = 1, CombativePlant2Id = 2) and
	 *      (CombativePlant1Id = 2, CombativePlant1Id = 1)
	 * are really the same entry, and we neither require nor do we want to have BOTH
	 */
	public function testInsertDuplicateValidCombativePlantEntry(){

	}

	/**
	 * test inserting an invalid plant entry (one that has an invalid (non-null) id)
	 */
	/* don't use this - it makes more sense to test attempt to
	 add duplicate entry - see testInsertDuplicateValidCombativePlantEntry()
	public function testInsertInvalidCombativePlantEntry(){

	}*/

	/**
	 * test updating a combative plant entry.
	 */
	public function testUpdateValidCombativePlantEntry(){

	}

	public function testUpdateValidCombativePlantEntryOrderInsensitive(){

	}

	/**
	 * test updating a Combative plant entry that does not exist
	 * @expectedException PDOException
	 */
	public function testUpdateInvalidCombativePlantEntry(){

	}

	/**
	 * test deleting a valid plant entry
	 */
	public function testDeleteValidCombativePlantEntry(){

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