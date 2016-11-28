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
	 * string of a valid plantArea start day
	 * @var int $VALID_PLANTAREASTARTDAY
	 **/

	protected $VALID_PLANTAREASTARTDAY = 1;

	/**
	 * string of a valid plantArea start day, test for update
	 * @var int $VALID_PLANTAREASTARTDAY2
	 **/

	protected $VALID_PLANTAREASTARTDAY2 = 2;

	/**
	 * string of a valid plantArea end day
	 * @var int $VALID_PLANTAREAENDDAY
	 **/
	protected $VALID_PLANTAREAENDDAY = 12;

	/**
	 * string of a valid plantArea start month
	 * @var int $VALID_PLANTAREASTARTMONTH
	 **/
	protected $VALID_PLANTAREASTARTMONTH = 1;

	/**
	 * string of a valid plantArea end month
	 * @var int $VALID_PLANTAREAENDMONTH
	 **/
	protected $VALID_PLANTAREAENDMONTH = 12;

	/**
	 * string of a valid plantArea number
	 * @var string $VALID_PLANTAREANUMBER
	 **/
	protected $VALID_PLANTAREANUMBER = "7a";

	/**
	 * @var Plant The variable which will contain a plant object used for testing purposes
	 */
	protected $plant;

	/**
	 *Create depend objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp() method first
		parent::setUp();
		// create and insert a Plant
		$this->plant = new Plant(null, "truffula tree", "this is a latin name", "green", "Primary food source for Brown Barbaloots", "tree", 5, 100, 5, 32, 99, "d" );
		$this->plant->insert($this->getPDO());
}

	/**
	 * test inserting a valid PlantArea and verify that the actual mySQL data matches
	 *
	 **/

	/**
	 * test validate date function passes valid dates and failse invalid ones.
	 */
	public function testValidateDate(){
		$this->assertTrue(PlantArea::validateDate(12, 4));
		$this->assertFalse(PlantArea::validateDate(2,30));
		$this->assertFalse(PlantArea::validateDate(15, 42));
	}


	public function testInsertValidPlantArea( ){
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea = PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(), $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDay(),$this->VALID_PLANTAREASTARTDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDay(),$this->VALID_PLANTAREAENDDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartMonth(),$this->VALID_PLANTAREASTARTMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndMonth(),$this->VALID_PLANTAREAENDMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaNumber(),$this->VALID_PLANTAREANUMBER);
	}

	/**
	 * test inserting a PlantArea that already exists
	 * @expectedException \Exception
	 *
	 */
	public function testInsertInvalidPlantArea () {
		// create a PlantArea with a non null plantArea id and watch it fail
		$plantArea = new PlantArea(GrowifyTest::INVALID_KEY, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());
	}

	/**
 	* test inserting a PlantArea, editing it, and then updating it
 	**/

	public function testUpdateValidPlantArea () {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// edit the PlantArea and update it in mySQL
		$plantArea->setPlantAreaStartMonthandDay($this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREASTARTDAY2);
		$plantArea->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea =PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(), $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDay(),$this->VALID_PLANTAREASTARTDAY2);
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
 	* @expectedException \PDOException
 	**/
	public function testUpdateInvalidPlantArea() {
		// create a PlantArea and try to delete it without actually inserting it
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->update($this->getPDO());
	}

	/**
 	* test creating a PlantArea and then deleting it
 	**/
	public function testDeleteValidPlantArea() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// delete the PlantArea from mySQL
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("plantArea"));
		$plantArea->delete($this->getPDO());

		// grab the date from mySQL and enforce the PlantArea does not exist
		$pdoPlantArea = PlantArea::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertNull($pdoPlantArea);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("plantArea"));
	}

	/**
 	* test grabbing all plantAreas
 	*
 	**/
	public function testGetAllValidPlantAreas() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = PlantArea::getAllPlantAreas($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\PlantArea", $results);

		// grab the result from the array and validate it
		$pdoPlantArea = $results[0];
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId() , $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDay(),$this->VALID_PLANTAREASTARTDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDay(),$this->VALID_PLANTAREAENDDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartMonth(),$this->VALID_PLANTAREASTARTMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndMonth(),$this->VALID_PLANTAREAENDMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaNumber(),$this->VALID_PLANTAREANUMBER);
	}

	public function testGetPlantAreaByPlantIdAndArea(){
		$numRows = $this->getConnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantArea = new PlantArea(null, $this->plant->getPlantId(), $this->VALID_PLANTAREASTARTDAY, $this->VALID_PLANTAREAENDDAY, $this->VALID_PLANTAREASTARTMONTH, $this->VALID_PLANTAREAENDMONTH, $this->VALID_PLANTAREANUMBER);
		$plantArea->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea = PlantArea::getPlantAreaByPlantIdAndAreaNumber($this->getPDO(), $this->plant->getPlantId(), $this->VALID_PLANTAREANUMBER);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plantArea"));
		$this->assertEquals($pdoPlantArea->getPlantAreaId(), $plantArea->getPlantAreaId());
		$this->assertEquals($pdoPlantArea->getPlantAreaPlantId(), $this->plant->getPlantId());
		$this->assertEquals($pdoPlantArea->getPlantAreaStartDay(),$this->VALID_PLANTAREASTARTDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndDay(),$this->VALID_PLANTAREAENDDAY);
		$this->assertEquals($pdoPlantArea->getPlantAreaStartMonth(),$this->VALID_PLANTAREASTARTMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaEndMonth(),$this->VALID_PLANTAREAENDMONTH);
		$this->assertEquals($pdoPlantArea->getPlantAreaNumber(),$this->VALID_PLANTAREANUMBER);


	}
}

