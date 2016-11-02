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
	 * @var Plant combativePlant1
	 */
	protected $combativePlant1 = null;
	/**
	 * Foreign key relationship to Plant.
	 * @var Plant combativePlant2
	 */
	protected $combativePlant2 = null;

	/**
	 * Create objects necessary for test.
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

}