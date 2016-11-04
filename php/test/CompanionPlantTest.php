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
	protected $companionPlant1 = null;
	/**
	 * Foreign key relationship to Plant
	 *
	 * @var Plant companionPlant2
	 */
	protected $companionPlant2 = null;

	/**
	 * Create objects necessary for test (dependencies)
	 */
	public final function setUp() {
		//run default setUp() method
		parent::setUp();

		// create and insert a Plant1
		$this->companionPlant1 = new Plant(/*TODO add parameters */);
		$this->companionPlant2->insert($this->getPDO());

		// create and insert a Plant2
		$this->companionPlant2 = new Plant(/*TODO add parameters */)
		$this->companionPlant2->insert($this->getPDO());
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
	 * e.g. (companionPlantId1 = 1, companionPlantId2 = 2) and
	 *      (companionPlantId1 = 2, companionPlantId2 = 2)
	 * are really the same entry, and we neither require nor do we want to have BOTH
	 **/
	public function testInsertDuplicateValidCompanionPlantEntry() {

	}

	/**
	 * test inserting an invalid plant entry (one that has an invalid (non-null) id)
	 *
	 *
	 */

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

}