<?php

namespace Edu\Cnm\Growify;
use Cnm\Edu\Growify\ZipCode;
use Cnm\Edu\Growify\Test\GrowifyTest;

require_once('GrowifyTest.php');
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/classes/autoload.php");
/**
* Full PHPUnit test for the ZipCode class
*
* This is a complete PHPUnit test of the ZipCode class. It is complete because *ALL* mySQL/PDO enabled methods
* are tested for both invalid and valid inputs.
*
* @see ZipCode
* @author Growify
**/
class ZipCodeTest extends GrowifyTest {
/**
* String of a valid Zipcode
* @var string $VALID_ZipCodeCode
**/
protected $VALID_ZIPCODECODE = "87002";
/**
* String of a valid ZipCodeArea
* @var string $VALID_ZIPCODEAREA
**/
protected $VALID_ZIPCODEAREA = "5b";

/**
* string of a valid zipCodeArea
 * @var string $VALID_ZIPCODEAREA
**/
protected $VALID_ZIPCODEAREA2  = "7a";

/**
 * string of an invalid zipCodeCode
 * @var string $INVALID_ZIPCODECODE
 */
protected $INVALID_ZIPCODECODE = "04200";
protected $zipCode = null;

public final function setUp() {
// run the default setUp() method first
parent::getSetUpOperation();

}

/**
* test inserting a valid ZipCode and verify that the actual mySQL data matches
**/
public function testInsertValidZipCode() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("zipCode");

$zipCode = new ZipCode($this->VALID_ZIPCODECODE,$this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

$pdozipCode = ZipCode::getZipCodeByZipCodeCode($this->getPDO(), $zipCode->getZipCodeCode());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$this->assertEquals($pdozipCode->getZipCodeCode(), $this->VALID_ZIPCODECODE);
$this->assertEquals($pdozipCode->getZipCodeArea(), $this->VALID_ZIPCODEAREA);
}

/**
* test inserting a ZipCode that already exists
*
* @expectedException PDOException
**/
public function testInsertInvalidZipCode() {
// create a ZipCode with a non null zipCodeCode and watch it fail
$zipCode = new ZipCode($this->INVALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());
}

/**
* test inserting a ZipCode, editing it, and then updating it
**/
public function testUpdateValidZipCode() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("zipCode");

// create a new ZipCode and insert to into mySQL
$zipCode = new ZipCode($this->VALID_ZIPCODECODE,$this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// edit the ZipCode and update it in mySQL
$zipCode->setZipCodeArea($this->VALID_ZIPCODEAREA2);
$zipCode->update($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$pdoZipCode = ZipCode::getZipCodebyZipCodeCode($this->getPDO(), $zipCode->getZipCodeArea());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$this->assertEquals($pdoZipCode->getZipCodeCode(), $this->$zipCode->getZipCodeCode());
$this->assertEquals($pdoZipCode->getZipCodeArea(), $this->VALID_ZIPCODEAREA2);
}

/**
* test updating a ZipCode that does not exist
*
* @expectedException PDOException
**/
public function testUpdateInvalidZipCode() {
// create a ZipCode, try to update it without actually updating it and watch it fail
$zipCode = new ZipCode($this->INVALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->update($this->getPDO());
}

/**
* test creating a ZipCode and then deleting it
**/
public function testDeleteValidZipCode() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("zipCode");

// create a new ZipCode and insert to into mySQL
$zipCode = new ZipCode($this->VALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// delete the zipCode from mySQL
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$zipCode->delete($this->getPDO());

// grab the data from mySQL and enforce the zipCode does not exist
$pdoZipCode = ZipCode::getZipCodeByZipCodeCode($this->getPDO(), $zipCode->getZipCodeCode());
$this->assertNull($pdoZipCode);
$this->assertEquals($numRows, $this->getConnection()->getRowCount("zipCode"));
}

/**
* test deleting a ZipCode that does not exist
*
* @expectedException PDOException
**/
public function testDeleteInvalidZipCode() {

$zipCode = new ZipCode($this->VALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->delete($this->getPDO());
}

/**
* test grabbing all ZipCodesphp
**/
public function testGetAllValidZipCodes() {
// count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("zipCode");

$zipCode = new ZipCode($this->VALID_ZIPCODECODE, $this->VALID_ZIPCODEAREA);
$zipCode->insert($this->getPDO());

// grab the data from mySQL and enforce the fields match our expectations
$results = ZipCode::getAllZipCodes($this->getPDO());
$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("zipCode"));
$this->assertCount(1, $results);
$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\php\\ZipCode", $results);

// grab the result from the array and validate it
$pdoZipCode = $results[0];
$this->assertEquals($pdoZipCode->getZipCodeCode(), $zipCode->getZipCodeCode());
$this->assertEquals($pdoZipCode->getZipCodeArea(), $zipCode->getZipCodeArea);
}

}