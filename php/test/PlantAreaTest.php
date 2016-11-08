<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant};
use PDOException;
use PlantArea;

// grab the project test parameters
require_once("GrowifyTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__)."/classes/autoload.php");

/**
 * Full PHPUnit test for the PlantArea class
 *
 * This is a complete PHPUnit test fo the PlantArea class because
 * all mySQL/PDO enabled methods are tested for valid and invalid inputs.
 *
 * @see PlantArea
 * @author Ana Vela <avela7@cnm.edu>
 *
 */
class PlantAreaTest extends GrowifyTest {
	/**
	 *Primary key relationship to Plant.
	 * @var    plantAreaId
	 **/
	protected $VALID_PLANTAREAID = "This is a valid plant area";

	/**
	 *
	 * @var    plantAreaPlantId
	 **/
	protected $VALID_PLANTAREAPLANTID = "This plant area has this plant id in the database";

	/**
	 *
	 * @var plantAreaStartDate
	 */

	protected $VALID_PLANTAREASTARTDATE = null;

	/**
	 *
	 * @var plantAreaEndDate
	 * */
	protected $VALID_PLANTAREAENDDATE = null;

	/**
	 *
	 * @var plantAreaNumber
	 **/

	protected $VALID_PLANTAREANUM = null;

	/**
	 *Create depend objects before running each test
	 */
public final function setUp() {
	// run the default setUp() method first
		parent::setUp();

		//create and insert a plant area id
		$this->plantAreaId = new Plant();
		$this->plantAreaId->insert($this->getPDO());

		//create and insert plant area plant id
		$this->plantAreaPlantId = new Plant();
		$this->plantAreaPlantId->insert ($this->getPDO());

		//create and insert plant area start date
		$this->plantAreaStartDate = new Plant();
		$this->plantAreaStartDate->insert($this->getPDO());

		//create and insert plant area end date
		$this->plantAreaEndDate = new Plant();
		$this->plantAreaStartDate->insert($this->getPDO());

		//create and insert plant area area num
		$this->plantAreaNum = new Plant();
		$this->plantAreaNum->insert($this->getPDO());
	}

	/**
	 * test inserting a valid PlantArea and verify that the actual mySQL data matches
	 * @expectedException PDOException
	 **/


	public function testInsertValidPlantArea($plantAreaId) {
		//count the number of rows and save it for later
		$numRows = $this->getconnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREAPLANTID, $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
		$plantAreaId->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea = PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertEquals($pdoPlantArea->getPlantId(), $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaId(), $this->VALID_PLANTAREAID);
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(),$this->VALID_PLANTAREAPLANTID);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTDATE);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDate(),$this->VALID_PLANTAREAENDDATE);
		$this->assertEquals($pdoPlantArea->getPlantAreaNum(),$this->VALID_PLANTAREANUM);
	}

	/**
	 * test inserting a PlantArea that already exists
	 *
	 * @expectedException PDOException
	 */
	public function testInsertInvalidPlantArea () {
		// create a PlantArea with a non null plant area id and watch it fail
		$plantArea = new PlantArea(GrowifyTest::INVALID_KEY, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
		$plantArea->insert($this->getPDO());
	}

/**
 * test inserting a PlantArea, editing it, and then updating it
 **/

public function testUpdateValidPlantArea ($plantArea) {
	// count the number of rows and save it for later
	$numRows = $this->getconnection()->getRowCount("plantArea");

	// create a new PlantArea and insert into mySQL
	$plantAreaId = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
	$plantAreaId->insert($this->getPDO());

	// edit the PlantArea and update it in mySQL
	$plantArea->setPlantAreaId($this->VALID_PLANTAREAID);
	$plantArea->update($this->getPDO());

	// grab the data from mySQL and enforce the fields match our expectations
	$pdoPlantArea =PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
	$this->assertEquals($pdoPlantArea->getPlantId(), $this->plant->getPlantId());
	$this->assertEquals($pdoPlantArea->getPlantAreaId(), $this->VALID_PLANTAREAID);
	$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(),$this->VALID_PLANTAREAPLANTID);
	$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTDATE);
	$this->assertEquals($pdoPlantArea->getPlantAreaEndDate(),$this->VALID_PLANTAREAENDDATE);
	$this->assertEquals($pdoPlantArea->getPlantAreaNum(),$this->VALID_PLANTAREANUM);
}

/**
 * test updating a PlantArea that that does not exist
 * @expectedException PDOException
 **/
public function testUpdateInvalidPlantArea($plantArea) {
	// create a PlantArea and try to delete it without actually inserting it
	$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
	$plantArea->update($this->getPDO());
}

/**
 * test creating a PlantArea and then deleting it
 **/
public function testDeleteValidPlantArea($plantArea) {
	// count the number of rows and save it for later
	$numRows = $this->getconnection()->getRowCount("plantArea");


	// create a new PlantArea and insert into mySQL
	$plantAreaId = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
	$plantAreaId->insert($this->getPDO());

	// delete the PlantArea from mySQL
	$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("plant area"));
	$plantArea->delete($this->getPDO());

	// grab the date from mySQL and enforce the PlantArea does not exist
	$pdoPlantArea = PlantArea::getPlantAreabyPlantAreaPlantId($this->getPDO(), $plantArea->getPlantId());
	$this->assertNull($pdoPlantArea);
	$this->assertEquals($numRows, $this->getConnection()->getRowCount("plant area"));
}

/**
 * test grabbing a PlantArea by number that does not exist
 **/
public function testGetInvalidPlantAreaByPlantAreaNum() {
	//grab a plant area by searching for a plant area number that does not exist

	$plantArea = PlantArea::GetPlantAreaByPlantAreaNum($this->getPDO(), "This plant area number is not in our system");
	$this->assertCount(0, $plantArea);
}

/**
 * test grabbing all plant areas
 *
 **/
public function testGetAllValidPlantAreas() {
	// count the number of rows and save it for later
	$numRows = $this->getconnection()->getRowCount("plantArea");

	// create a new PlantArea and insert into mySQL
	$plantAreaId = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDATE, $this->VALID_PLANTAREAENDDATE, $this->VALID_PLANTAREANUM);
	$plantAreaId->insert($this->getPDO());

	// grab the data from mySQL and enforce the fields match our expectations
	$results =PlantArea::getAllPlantAreas($this->getPDO());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant area"));
	$this->assertCount(1, $results);
	$this->assertContainsInstancesOf("Edu\\Cnm\\Growify\\PlantArea", $results);

	// grab the result from the array and validate it
	$pdoPlantArea = $results[0];
	$this->assertEquals($pdoPlantArea->getPlantId(), $this->plant->getPlantId());
	$this->assertEquals($pdoPlantArea->getPlantAreaId(),$this->VALID_PLANTAREAID);
	$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(),$this->VALID_PLANTAREAPLANTID);
	$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTDATE);
	$this->assertEquals($pdoPlantArea->getPlantAreaEndDate(),$this->VALID_PLANTAREAENDDATE);
	$this->assertEquals($pdoPlantArea->getPlantAreaNum(),$this->VALID_PLANTAREANUM);
}



}

