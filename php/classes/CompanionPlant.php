<?php

/**
 * Creating class for CompanionPlant
 *
 * This is the class for the CompanionPlant for the Growify capstone.
 *
 * @author Ana Vela <avela7@cnm.edu>
 * @version 1.0.0
 **/


class CompanionPlant {
/**
 *
 *  first CompanionPlant
 * @var int $companionPlant1
 **/
	private $companionPlant1;

	/**
	 *
	 * second CompanionPlant
	 * @var int $companionPlant2
	 *
	 **/

	private $companionPlant2;

	/**
	 * constructor for this CompanionPlant
	 *
	 * @param $newCompanionPlant1
	 * @param $newCompanionPlant2
	 * @throws Exception if some other exception occurs
	 * @throws TypeError if data types violate type hints
	 * @internal param int|null $companionPlant1 first CompanionPlant
	 * @internal param int|null $companionPlant2 second CompanionPlant
	 *
	 **/
	public function _construct($newCompanionPlant1, $newCompanionPlant2) {
		try {
			$this->setCompanionPlant1($newCompanionPlant1);
			$this->setCompanionPlant2($newCompanionPlant2);
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
	 * accessor method for companion plant 1
	 *
	 * @return int value of companion plant 1
	 **/
	public function getCompanionPlant1() {
		return ($this->companionPlant1);
	}

	/**
	 * mutator method for this companion plant 1
	 *
	 * @param int|null $newCompanionPlant1 new value of companion plant 1
	 * @throws \RangeException if $newCompanionPlant1 is not positive
	 * @throws \TypeError if $newCompanionPlant1 is not an integer
	 **/
	public function setCompanionPlant1
	($newCompanionPlant1) {
		// verify the companion plant 1 is positive
		if($newCompanionPlant1<= 0) {
			throw(new \RangeException("companion plant is not a positive"));
		}
		// convert and store the companion plant 1
		$this->companionPlant1 =$newCompanionPlant1;
	}
	/**
	 * accessor method for companion plant 2
	 *
	 * @return int value for companion plant 2
	 **/
	public function getCompanionPlant2() {
		return ($this->companionPlant2);
	}
	/**
	 * mutator method for this companion plant 2
	 *
	 * @param int|null $newCompanionPlant2 new value of companion plant 2
	 * @throws \RangeException if $newCompanionPlant2 is not positive
	 * @throws \TypeError if $newCompanionPlant2 is not an integer
	 *
	 **/

	public function setCompanionPlant2
	($newCompanionPlant2) {
		// verify the companion plant 2 is positive
		if($newCompanionPlant2<= 0) {
			throw(new \RangeException("companion plant is not a positive"));
	}
	 // convert and store the companion plant 2
		$this->companionPlant2 =$newCompanionPlant2;
	}
}