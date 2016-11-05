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
 *  id for first CompanionPlant
 * @var int $companionPlant1Id
 **/
	private $companionPlant1Id;

	/**
	 *
	 * id for second CompanionPlant
	 * @var int $companionPlant2Id
	 *
	 **/

	private $companionPlant2Id;

	/**
	 * constructor for this CompanionPlant
	 *
	 * @param $newCompanionPlant1Id
	 * @param $newCompanionPlant2Id
	 * @throws Exception if some other exception occurs
	 * @throws TypeError if data types violate type hints
	 * @internal param int|null $companionPlant1Id first CompanionPlant
	 * @internal param int|null $companionPlant2Id second CompanionPlant
	 *
	 **/
	public function _construct($newCompanionPlant1Id, $newCompanionPlant2Id) {
		try {
			$this->setCompanionPlant1Id($newCompanionPlant1Id);
			$this->setCompanionPlant2Id($newCompanionPlant2Id);
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
	 * accessor method for companion plant 1 id
	 *
	 * @return int value of companion plant 1 id
	 **/
	public function getCompanionPlant1Id() {
		return ($this->companionPlant1Id);
	}

	/**
	 * mutator method for this companion plant 1 id
	 *
	 * @param int|null $newCompanionPlant1 new value of companion plant 1 id
	 * @throws \RangeException if $newCompanionPlant1Id is not positive
	 * @throws \TypeError if $newCompanionPlant1Id is not an integer
	 **/
	public function setCompanionPlant1Id
	($newCompanionPlant1Id) {
		// verify the companion plant 1 is positive
		if($newCompanionPlant1Id<= 0) {
			throw(new \RangeException("companion plant is not a positive"));
		}
		// convert and store the companion plant 1 id
		$this->companionPlant1 =$newCompanionPlant1Id;
	}
	/**
	 * accessor method for companion plant 2 id
	 *
	 * @return int value for companion plant 2 id
	 **/
	public function getCompanionPlant2Id() {
		return ($this->companionPlant2Id);
	}
	/**
	 * mutator method for this companion plant 2
	 *
	 * @param int|null $newCompanionPlant2Id new value of companion plant 2
	 * @throws \RangeException if $newCompanionPlant2Id is not positive
	 * @throws \TypeError if $newCompanionPlant2Id is not an integer
	 *
	 **/

	public function setCompanionPlant2Id
	($newCompanionPlant2Id) {
		// verify the companion plant 2 is positive
		if($newCompanionPlant2Id<= 0) {
			throw(new \RangeException("companion plant is not a positive"));
	}
	 // convert and store the companion plant 2
		$this->companionPlant2Id =$newCompanionPlant2Id;
	}
}