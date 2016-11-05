<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant};


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
	 ***/
	protected $companionPlant1Id = null;
	/**
	 * Foreign key relationship to Plant
	 *
	 * @var Plant companionPlant2
	 */
	protected $companionPlant2Id = null;

	/**
	 * Create objects necessary for test (dependencies)
	 */
	public final function setUp() {
		//run default setUp() method
		parent::setUp();

		// create and insert a Plant1Id
		$this->companionPlant1Id = new Plant(/*TODO add parameters */);
		$this->companionPlant1Id->insert($this->getPDO());

		// create and insert a Plant2Id
		$this->companionPlant2Id = new Plant(/*TODO add parameters */)
		$this->companionPlant2Id->insert($this->getPDO());
	}
 	/**
	 * insert a companion plant entry and verify that the mySQL entry data matches.
	 * @expectedException PDOException
	 **/
	public function testInsertValidCompanionPlantEntry() {

	}

	/**
	 * do we get expected behavior when attempting to create duplicate entry
	 * in other words, we expect NOT to be able to insert an identical entry
	 *
	 * this includes reversing the fields
	 * e.g. (companionPlant1Id = 1, companionPlant2Id = 2) and
	 *      (companionPlant1Id = 2, companionPlant2Id = 2)
	 * are really the same entry, and we neither require nor do we want to have BOTH
	 **/
	public function testInsertDuplicateValidCompanionPlantEntry() {

	}

	/**
	 * test inserting an invalid plant entry (one that has an invalid (non-null) id)
	 **/
	/**
	 * don't use this - it makes more sense to test attempt to add duplicate entry - see testInsertDuplicateValidCompanionPlantEntry() public function testInsertInvalidCompanionPlantEntry()
	 **/

/**
 *  test updating a companion plant entry
 **/
public function testUpdateValidCompanionPlantEntry(){

}
/**
 * test updating a companion plant entry that does not exist
 * @expectedException PDOException
 **/
	public function testUpdateInvalidCompanionPlantEntry() {

	}

	/**
	 * test deleting a valid plant entry
	 */
	public function testDeletValidCompanionPlantEntry(){

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