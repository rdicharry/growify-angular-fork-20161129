<?php

/**
 * Creating class for PlantArea
 *
 * This is the class for the PlantArea for the Growify capstone.
 *
 * @author Ana Vela <avela7@cnm.edu>
 * @version 1.0.0
 *
 * attributes -
 *plantAreaId, plantAreaPlantId, plantAreaStartDate, plantAreaEndDate, plantAreaAreaNum
 */
class PlantArea {
	/**
	 * id for this PlantArea; this is the primary key
	 * @var int $plantAreaId
	 **/
	private $plantAreaId;

	/**
	 * plant id with this PlantArea
	 * @var int $plantAreaPlantId
	 **/
	private $plantAreaPlantId;

	/**
	 * start date for this PlantArea
	 * @var \DateTime $plantAreaStartDate
	 **/
	private $plantAreaStartDate;

	/**
	 * end date for this PlantArea
	 * @var \DateTime $plantAreaEndDate
	 *
	 **/
	private $plantAreaEndDate;

	/**
	 * area number for this PlantArea
	 * @var int $plantAreaAreaNum
	 */
	private $plantAreaAreaNum;

	/**
	 * constructor for this PlantArea
	 *
	 * @param $newPlantAreaId
	 * @param $newPlantAreaPlantId
	 * @param $newPlantAreaStartDate
	 * @param $newPlantAreaEndDate
	 * @param $newPlantAreaAreaNum
	 * @throws Exception if some other exception occurs
	 * @throws TypeError if data types violate type hints
	 * @internal param int|null $plantAreaId id for this PlantId
	 * @internal param null|string $plantAreaPlantId plant id for this plant area
	 * @internal param DateTime|Null|String $plantAreaStartDate start date for this PlantArea
	 * @internal param DateTime|Null|String $plantAreaStartDate end date for this PlantArea
	 * @internal param int|null $plantAreaAreaNum the area number of this PlantArea
	 */
	public function _construct($newPlantAreaId, $newPlantAreaPlantId, $newPlantAreaStartDate, $newPlantAreaEndDate, $newPlantAreaAreaNum) {
		try {
			$this->setPlantAreaId($newPlantAreaId);
			$this->setPlantAreaPlantId($newPlantAreaPlantId);
			$this->setPlantAreaStartDate($newPlantAreaStartDate);
			$this->setPlantAreaEndDate($newPlantAreaEndDate);
			$this->setPlantAreaAreaNum($newPlantAreaAreaNum);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the execption to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), $invalidArgument));
		} catch(\RangeException $range) {
			//rethrow the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			//rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			//rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}


	/**
	 * accessor method for plant area id
	 *
	 * @return int|null value of plant area id
	 **/
	public function getPlantAreaId() {
		return ($this->plantAreaId);
	}

	/**
	 * mutator method for plant area id
	 *
	 * @param int|null $newPlantAreaId new value of plant area id
	 * @throws \RangeException if $newPlantAreaId is not positive
	 * @throws \TypeError if $newPlantAreaid is not an integer
	 **/
	public function setPlantAreaId($newPlantAreaId) {
		// verify the plant area id is positive
		if($newPlantAreaId <= 0) {
			throw(new \RangeException("plant area id is not positive"));
		}
		// convert and store the plant area id
		$this->plantAreaId = $newPlantAreaId;
	}

	/**
	 * accessor method for plant area profile id
	 *
	 * @return int value of plant area profile id
	 **/
	public function getPlantAreaPlantId() {
		return ($this->plantAreaPlantId);
	}

	/**
	 * mutator method for plant area plant id
	 *
	 * @param int $newPlantAreaPlantId new value of plant area plant id
	 * @throws \RangeException if $newPlantAreaPlantId is not positive
	 * @throws \TypeError if $newPlantAreaPlantId is not an integer
	 **/
	public function setPlantAreaPlantId($newPlantAreaPlantId) {
		// verify the plant area profile id is positive
		if($newPlantAreaPlantId <= 0) {
			throw(new \RangeException("plant area plant id is not positive"));
		}
		// convert and store the plant area plant id
		$this->plantAreaPlantId = $newPlantAreaPlantId;
	}

	/**
	 * accessor method for plant area start date
	 *
	 * $return \DateTime value of plant area start date
	 **/
	public function getPlantAreaStartDate() {
		return ($this->plantAreaStartDate);
	}

	/**
	 * mutator method for plant area start date
	 *
	 * @param \DateTime|string|null $newPlantAreaStartDate plant area start date as a DateTime object or string
	 * @throw \InvalidArgumentException if $newPlantAreaStartDate is not a valid object or string
	 * @throws \RangeException if $newPlantAreaStartDate is a date that does not exist
	 **/
	public function setPlantAreaStartDate($newPlantAreaStartDate = null) {
		// base case: if the date is null, use the current date and time?????
		if($newPlantAreaStartDate === null) {
			$this->plantAreaStartDate = new \DateTime();
			return;
		}
	}

	/**
	 * accessor method for plant area end date
	 *
	 * $return \DateTime value of plant area end date
	 **/

	public
	function getPlantAreaEndDate() {
		return ($this->plantAreaEndDate);
	}


	/**
	 * mutator method for plant area end date
	 *
	 * @param \DateTime|string|null $newPlantAreaEndDate plant area end date as a DateTime object or string
	 * @throw \InvalidArgumentException if $newPlantAreaEndDate is not a valid object or string
	 * @throws \RangeException if $newPlantAreaEndDate is a date that does not exist
	 **/
	public function setPlantAreaEndDate($newPlantAreaEndDate) {
		$this->plantAreaEndDate = $newPlantAreaEndDate;
	}

	/**
	 * accessor method for plant area area number
	 *
	 * @return int value of plant area profile id
	 **/
	public function getPlantAreaAreaNum() {
		return ($this->plantAreaAreaNum);
	}

	/**
	 * @mutator method for plant area area number
	 *
	 * @param $newPlantAreaAreaNum
	 * @internal param int $newPlantAreaPlantId new value of plant area plant id
	 */
	public function setPlantAreaAreaNum($newPlantAreaAreaNum) {
		// verify the plant area profile id is positive
		if($newPlantAreaAreaNum <= 0) {
			throw(new \RangeException("plant area area number is not positive"));
		}
		// convert and store the plant area area number
		$this->plantAreaAreaNum = $newPlantAreaAreaNum;
	}

}

