<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\PlantArea;
use Edu\Cnm\Growify\Plant;

// grab the project test parameters
require_once("GrowifyTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");

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
	 * @var int $VALID_PLANTAREAID
	 **/
	protected $VALID_PLANTAREAID = "This is a valid plant area";



	/**
	 * string of a valid plant area start day
	 * @var int $VALID_PLANTAREASTARTDAY
	 **/

	protected $VALID_PLANTAREASTARTDAY = 1;

	/**
	 * string of a valid plant area end day
	 * @var int $VALID_PLANTAREAENDDAY
	 **/
	protected $VALID_PLANTAREAENDDAY = 12;

	/**
	 * string of a valid plant area start month
	 * @var int $VALID_PLANTAREASTARTMONTH
	 **/
	protected $VALID_PLANTAREASTARTMONTH = 1;

	/**
	 * string of a valid plant area end month
	 * @var int $VALID_PLANTAREAENDMONTH
	 **/
	protected $VALID_PLANTAREAENDMONTH = 12;

	/**
	 * string of a valid plant area number
	 * @var string $VALID_PLANTAREANUM
	 **/
	protected $VALID_PLANTAREANUMBER = "7a";

	/**
	 * @var \Plant The variable which will contain a plant object used for testing purposes
	 */
	protected $plant;

	//protected $plantId; Dont see why this is necessary

	/**
	 *Create depend objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp() method first
		parent::setUp();
		$this->plant = new Plant(null, "minitomato","smallest", "round and shiny", "fruity", 8, 10, 44, 45, 71, "t");
		$this->plant->insert($this->getPDO());

}

	/**
	 * test inserting a valid PlantArea and verify that the actual mySQL data matches
	 *
	 **/


	public function testInsertValidPlantArea( ){
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea = PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertEquals($pdoPlantArea->getPlantId(), $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaId(), $this->VALID_PLANTAREAID);
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(),$this->VALID_PLANTAREAPLANTID);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTDATE);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDate(),$this->VALID_PLANTAREAENDDATE);
		$this->assertEquals($pdoPlantArea->getPlantAreaNumber(),$this->VALID_PLANTAREANUMBER);
	}

	/**
	 * test inserting a PlantArea that already exists
	 *
	 *
	 */
	public function testInsertInvalidPlantArea () {
		// create a PlantArea with a non null plant area id and watch it fail
		$plantArea = new PlantArea($this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH,$this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());
	}

	/**
 	* test inserting a PlantArea, editing it, and then updating it
 	**/

	public function testUpdateValidPlantArea () {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea($this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// edit the PlantArea and update it in mySQL
		$plantArea->setPlantAreaId($this->VALID_PLANTAREAID);
		$plantArea->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea =PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertEquals($pdoPlantArea->getPlantAreaId(), $this->VALID_PLANTAREAID);
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId($this->getPDO(),$plantArea->getPlantAreaId()),$plantArea->getPlantAreaId());
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDay(),$this->VALID_PLANTAREASTARTDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDay(),$this->VALID_PLANTAREAENDDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartMonth(),$this->VALID_PLANTAREASTARTMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndMonth(),$this->VALID_PLANTAREAENDMONTH);
		$this->assertEquals($pdoPlantArea->getplantAreaNumber(),$this->VALID_PLANTAREANUMBER);
	}

	// -------------------------------------***** LEFT OFF HERE
	//
	// ******------------------------****************************

	/**
 	* test updating a PlantArea that that does not exist
 	*
 	**/
	public function testUpdateInvalidPlantArea() {
		// create a PlantArea and try to delete it without actually inserting it
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->update($this->getPDO());
	}

	/**
 	* test creating a PlantArea and then deleting it
 	**/
	public function testDeleteValidPlantArea() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");


		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY,  $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

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
	public function testGetInvalidPlantAreaByPlantAreaNumber() {
		//grab a plant area by searching for a plant area number that does not exist

		$plantArea = PlantArea::GetPlantAreaByPlantAreaNumber($this->getPDO(), "This plant area number is not in our system");
		$this->assertCount(0, $plantArea);
	}

	/**
 	* test grabbing all plant areas
 	*
 	**/
	public function testGetAllValidPlantAreas() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID, $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

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
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDate(),$this->VALID_PLANTAREAENDDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREASTARTMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDate(),$this->VALID_PLANTAREAENDMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaNumber(),$this->VALID_PLANTAREANUMBER);
	}
}

