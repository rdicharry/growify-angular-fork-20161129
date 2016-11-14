<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\Plant;

require_once("GrowifyTest.php");

require_once(dirname(__DIR__)."/classes/autoload.php");
/**
 * PlantTest Class
 *
 * This PlantTest will .
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class PlantTest extends GrowifyTest {
	/**
	 * name of the plant
	 * @var string $VALID_PLANTNAME
	 **/
	protected $VALID_PLANTNAME = "Piranha Plant";
	/**
	 * 2nd name of the plant, for testing edits and updates
	 * @var string $VALID_PLANTNAME2
	 **/
	protected $VALID_PLANTNAME2 = "Pakkun Flower";
	/**
	 * variety of the plant
	 * @var string $VALID_PLANTVARIETY
	 **/
	protected $VALID_PLANTVARIETY = "standard";
	/**
	 * description of the plant
	 * @var string $VALID_PLANTDESCRIPTION
	 **/
	protected $VALID_PLANTDESCRIPTION = "Live in green pipes. Red with white spots and has razor sharp teeth";
	/**
	 * type of the plant
	 * @var string $VALID_PLANTTYPE
	 **/
	protected $VALID_PLANTTYPE = "flower";
	/**
	 * space to give the plant
	 * @var int $VALID_PLANTSPREAD
	 **/
	protected $VALID_PLANTSPREAD = 5;
	/**
	 * amount of days for the plant to mature
	 * @var int $VALID_PLANTDAYSTOHARVEST
	 **/
	protected $VALID_PLANTDAYSTOHARVEST = 2;
	/**
	 * height of the plant
	 * @var int $VALID_PLANTHEIGHT
	 **/
	protected $VALID_PLANTHEIGHT = 4;
	/**
	 * minimum temperature for the plant to grow
	 * @var int $VALID_PLANTMINTEMP
	 **/
	protected $VALID_PLANTMINTEMP = 50;
	/**
	 * maximum temperature for the plant to grow
	 * @var int $VALID_PLANTMAXTEMP
	 **/
	protected $VALID_PLANTMAXTEMP = 90;
	/**
	 * soil moisture needs for the plant
	 * @var string $VALID_PLANTSOILMOISTURE
	 **/
	protected $VALID_PLANTSOILMOISTURE = "D";
	/**
	 * The plant being tested
	 * @var Plant plant
	 **/
	protected $plant = null;

	public final function setUp() {
		// run the default setUp() method
		parent::setUp();
	}

	/**
	 * test inserting a valid Plant and verify that the actual mySQL data matches
	 **/
	public function testInsertValidPlant() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlant = Plant::getPlantByPlantId($this->getPDO(), $plant->getPlantId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$this->assertEquals($pdoPlant->getPlantName(), $this->VALID_PLANTNAME);
		$this->assertEquals($pdoPlant->getPlantVariety(), $this->VALID_PLANTVARIETY);
		$this->assertEquals($pdoPlant->getPlantDescription(), $this->VALID_PLANTDESCRIPTION);
		$this->assertEquals($pdoPlant->getPlantType(), $this->VALID_PLANTTYPE);
		$this->assertEquals($pdoPlant->getPlantSpread(), $this->VALID_PLANTSPREAD);
		$this->assertEquals($pdoPlant->getPlantDaysToHarvest(), $this->VALID_PLANTDAYSTOHARVEST);
		$this->assertEquals($pdoPlant->getPlantHeight(), $this->VALID_PLANTHEIGHT);
		$this->assertEquals($pdoPlant->getPlantMinTemp(), $this->VALID_PLANTMINTEMP);
		$this->assertEquals($pdoPlant->getPlantMaxTemp(), $this->VALID_PLANTMAXTEMP);
		$this->assertEquals($pdoPlant->getPlantSoilMoisture(), $this->VALID_PLANTSOILMOISTURE);
	}

	/**
	 * test inserting a Plant that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidPlant() {
		// create a Plant with a non null plant id and watch it fail
		$plant = new Plant(GrowifyTest::INVALID_KEY, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());
	}

	/**
	 * test inserting a Plant, editing it, and then updating it
	 **/
	public function testUpdateValidPlant() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// edit the Plant and update it in mySQL
		$plant->setPlantName($this->VALID_PLANTNAME2);
		$plant->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoPlant = Plant::getPlantByPlantId($this->getPDO(), $plant->getPlantId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$this->assertEquals($pdoPlant->getPlantName(), $this->VALID_PLANTNAME2);
		$this->assertEquals($pdoPlant->getPlantVariety(), $this->VALID_PLANTVARIETY);
		$this->assertEquals($pdoPlant->getPlantDescription(), $this->VALID_PLANTDESCRIPTION);
		$this->assertEquals($pdoPlant->getPlantType(), $this->VALID_PLANTTYPE);
		$this->assertEquals($pdoPlant->getPlantSpread(), $this->VALID_PLANTSPREAD);
		$this->assertEquals($pdoPlant->getPlantDaysToHarvest(), $this->VALID_PLANTDAYSTOHARVEST);
		$this->assertEquals($pdoPlant->getPlantHeight(), $this->VALID_PLANTHEIGHT);
		$this->assertEquals($pdoPlant->getPlantMinTemp(), $this->VALID_PLANTMINTEMP);
		$this->assertEquals($pdoPlant->getPlantMaxTemp(), $this->VALID_PLANTMAXTEMP);
		$this->assertEquals($pdoPlant->getPlantSoilMoisture(), $this->VALID_PLANTSOILMOISTURE);
	}

	/**
	 * test updating a Plant that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidPlant() {
		// create a Plant, try to update it without actually inserting it and watch it fail
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->update($this->getPDO());
	}

	/**
	 * test creating a Plant and then deleting it
	 **/
	public function testDeleteValidPlant() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// delete the Plant from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$plant->delete($this->getPDO());

		// grab the data from mySQL and enforce the Plant does not exist
		$pdoPlant = Plant::getPlantByPlantId($this->getPDO(), $plant->getPlantId());
		$this->assertNull($pdoPlant);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("plant"));
	}

	/**
	 * test deleting a Plant that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidPlant() {
		// create a Plant and try to delete it without actually inserting it
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->delete($this->getPDO());
	}

	/**
	 * test grabbing a Plant by plant name
	 **/
	public function testGetValidPlantByPlantName() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Plant::getPlantByPlantName($this->getPDO(), $plant->getPlantName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Plant", $results);

		// grab the result from the array and validate it
		$pdoPlant = $results[0];
		$this->assertEquals($pdoPlant->getPlantName(), $this->VALID_PLANTNAME);
		$this->assertEquals($pdoPlant->getPlantVariety(), $this->VALID_PLANTVARIETY);
		$this->assertEquals($pdoPlant->getPlantDescription(), $this->VALID_PLANTDESCRIPTION);
		$this->assertEquals($pdoPlant->getPlantType(), $this->VALID_PLANTTYPE);
		$this->assertEquals($pdoPlant->getPlantSpread(), $this->VALID_PLANTSPREAD);
		$this->assertEquals($pdoPlant->getPlantDaysToHarvest(), $this->VALID_PLANTDAYSTOHARVEST);
		$this->assertEquals($pdoPlant->getPlantHeight(), $this->VALID_PLANTHEIGHT);
		$this->assertEquals($pdoPlant->getPlantMinTemp(), $this->VALID_PLANTMINTEMP);
		$this->assertEquals($pdoPlant->getPlantMaxTemp(), $this->VALID_PLANTMAXTEMP);
		$this->assertEquals($pdoPlant->getPlantSoilMoisture(), $this->VALID_PLANTSOILMOISTURE);
	}

	/**
	 * test grabbing a Plant by name that does not exist
	 **/
	public function testGetInvalidPlantByPlantName() {
		// grab a plant by searching for name that does not exist
		$plant = Plant::getPlantByPlantName($this->getPDO(), "This is not a plant");
		$this->assertCount(0, $plant);
	}

	/**
	 * test grabbing a Plant by plant type
	 **/
	public function testGetValidPlantByPlantType() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Plant::getPlantByPlantType($this->getPDO(), $plant->getPlantType());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Plant", $results);

		// grab the result from the array and validate it
		$pdoPlant = $results[0];
		$this->assertEquals($pdoPlant->getPlantName(), $this->VALID_PLANTNAME);
		$this->assertEquals($pdoPlant->getPlantVariety(), $this->VALID_PLANTVARIETY);
		$this->assertEquals($pdoPlant->getPlantDescription(), $this->VALID_PLANTDESCRIPTION);
		$this->assertEquals($pdoPlant->getPlantType(), $this->VALID_PLANTTYPE);
		$this->assertEquals($pdoPlant->getPlantSpread(), $this->VALID_PLANTSPREAD);
		$this->assertEquals($pdoPlant->getPlantDaysToHarvest(), $this->VALID_PLANTDAYSTOHARVEST);
		$this->assertEquals($pdoPlant->getPlantHeight(), $this->VALID_PLANTHEIGHT);
		$this->assertEquals($pdoPlant->getPlantMinTemp(), $this->VALID_PLANTMINTEMP);
		$this->assertEquals($pdoPlant->getPlantMaxTemp(), $this->VALID_PLANTMAXTEMP);
		$this->assertEquals($pdoPlant->getPlantSoilMoisture(), $this->VALID_PLANTSOILMOISTURE);
	}
	
	/**
	 * test grabbing a Plant by a type that does not exist
	 **/
	public function testGetInvalidPlantByPlantType() {
	// grab a plant by searching for type that does not exist
	$plant = Plant::getPlantByPlantType($this->getPDO(), "This is not a plant");
	$this->assertCount(0, $plant);
}
	/**
	 * test grabbing all Plants
	 **/
	public function testGetAllValidPlants() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("plant");

		// create a new Plant and insert to into mySQL
		$plant = new Plant(null, $this->VALID_PLANTNAME, $this->VALID_PLANTVARIETY, $this->VALID_PLANTDESCRIPTION, $this->VALID_PLANTTYPE, $this->VALID_PLANTSPREAD, $this->VALID_PLANTDAYSTOHARVEST, $this->VALID_PLANTHEIGHT, $this->VALID_PLANTMINTEMP, $this->VALID_PLANTMAXTEMP, $this->VALID_PLANTSOILMOISTURE);
		$plant->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Plant::getAllPlants($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("plant"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Plant", $results);

		// grab the result from the array and validate it
		$pdoPlant = $results[0];
		$this->assertEquals($pdoPlant->getPlantName(), $this->VALID_PLANTNAME);
		$this->assertEquals($pdoPlant->getPlantVariety(), $this->VALID_PLANTVARIETY);
		$this->assertEquals($pdoPlant->getPlantDescription(), $this->VALID_PLANTDESCRIPTION);
		$this->assertEquals($pdoPlant->getPlantType(), $this->VALID_PLANTTYPE);
		$this->assertEquals($pdoPlant->getPlantSpread(), $this->VALID_PLANTSPREAD);
		$this->assertEquals($pdoPlant->getPlantDaysToHarvest(), $this->VALID_PLANTDAYSTOHARVEST);
		$this->assertEquals($pdoPlant->getPlantHeight(), $this->VALID_PLANTHEIGHT);
		$this->assertEquals($pdoPlant->getPlantMinTemp(), $this->VALID_PLANTMINTEMP);
		$this->assertEquals($pdoPlant->getPlantMaxTemp(), $this->VALID_PLANTMAXTEMP);
		$this->assertEquals($pdoPlant->getPlantSoilMoisture(), $this->VALID_PLANTSOILMOISTURE);
	}
}
