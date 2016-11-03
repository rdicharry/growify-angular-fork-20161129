<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Plant, PlantArea};

// grab the project test parameters
require_once("GrowifyTest.php");

// grab the class under scrutiny
require_once("PlantAreaTest.php");

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
	protected $plantAreaId = null;

	/**
	 *
	 * @var    plantAreaPlantId
	 **/
	protected $plantAreaPlantId = null;

	/**
	 *
	 * @var plantAreaStartDate
	 */

	protected $plantAreaStartDate = null;

	/**
	 *
	 * @var plantAreaEndDate
	 * */
	protected $plantAreaEndDate = null;

	/**
	 *
	 * @var plantAreaNumber
	 **/

	protected $plantAreaNumber = null;

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
	 *  insert a plant area id entry and verify that the mySQL data matches.
	 * @expectedException PDOException
	 **/
	public function
	testInsertValidPlantAreaId() {
	}



}

