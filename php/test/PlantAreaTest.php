<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant, PlantArea};

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


	public function testInsertValidPlantArea() {
		//count the number of rows and save it for later
		$numRows = $this->getconnection()->getRowCount("plantArea");

		// create a new PlantArea and insert into mySQL
		$plantAreaId = new PlantAreaId(null, $this->plant->getPlantId(), $this->VALID_PLANTAREAID);
		$plantAreaId->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlantArea =PlantArea:::getPlantAreaByPlantAreaId($this->getPDO(), $plantArea->getPlantAreaId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowcount("plantArea"));
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

}

