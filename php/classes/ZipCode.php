<?php
/**
 * Created by PhpStorm.
 * User: Daniel Eaton
 * Date: 11/2/2016
 * Time: 3:58 PM
 */

class ZipCode{
	/**
	 * @var String the Zip Code corresponding to a USDA Grow Zone (zipCodeArea)
	 */
	private $zipCodeCode;

	/**
	 * @var int the zipCodeArea USDA GrowZone that corresponds to a United States Postal ZipCode
	 */
	private $zipCodeArea;


	public function __construct($zipCodeCode, $zipCodeArea) {
		$this->setZipCodeCode($zipCodeCode);
		$this->setZipCodeArea($zipCodeArea);
	}

	/**
	 * Sets the value of this ZipCode object's zipCodeCode to zipCodeCode
	 * @param $zipCodeCode string the value of zipCodeArea
	 *
	 * @throws TypeError if the parameter $zipCodeCode is not a string
	 * @throws OutOfBoundsException if the parameter $zipCodeCode is not 5 characters long (the length of a New Mexico Zipcode)
	 * @throws InvalidArgumentException if the parameter $zipCodeCode does not begin with 87 or 88 (The only beginning characters of a New Mexico ZipCode)
	 */
	public function setZipCodeCode($zipCodeCode) {
		//Validates $zipCodeCode to make sure it is a string that is 5 characters long beginning with either 87 or 88
		if(!is_string($zipCodeCode)){
			throw (new \TypeError('The $zipCodeCode Entered is not a String'));
		}elseif(strlen($zipCodeCode) != 5){
			throw (new \OutOfBoundsException('The $zipCodeCode Entered is not 5 characters long and is therefore an invalid New Mexico Zip Code'));
		}
		elseif(substr($zipCodeCode,0,1) != '87' &&  substr($zipCodeCode, 0,1) !='88'){
			throw (new \InvalidArgumentException('The $zipCodeCode entered is not a valid New Mexico Zip Code'));
		}

		$this->$zipCodeCode = $zipCodeCode;
	}

	/**
	 * Sets the value of this ZipCode object's zipCodeArea to zipCodeArea
	 *
	 * @param int $zipCodeArea the zipCodeArea that will be set
	 * @throws TypeError if the parameter $zipCodeArea is not an integer
	 * @throws RangeException if the parameter $zipCodeArea is above the value of 3 (The highest New Mexico Growing Zone)
	 * @throws RangeException if the parameter $zipCodeArea is below the value of 1 (The highest New Mexico Growing Zone)
	 */
	public function setZipCodeArea($zipCodeArea) {
		//Validates the Zip code area, making sure that it is an intenger and between 1-3 in value (The three NM growing zones)
		if(!is_int($zipCodeArea)){
			throw (new \TypeError("This zip code area is not an integer"));
		}elseif($zipCodeArea > 3){
			throw (new RangeException("This zip code area is not a valid New Mexco growing zone"));
		}elseif($zipCodeArea < 1){
			throw (new RangeException("This zip code area is not a valid New Mexco growing zone"));
		}
		//Set this object's value of zipCodeArea to the specified ZipCodeArea in the Parameter
		$this->$zipCodeArea;
	}

	/**
	 * @return int $zipCodeArea The area associated with a Zipcode Object
	 */
	public function getZipCodeArea() {
		return $this->zipCodeArea;
	}

	 /**
	 * @return String $zipCodeCode The Zipcode of the a ZipCode Object
	 */
	public function getZipCodeCode() {
		return $this->zipCodeCode;
	}

}