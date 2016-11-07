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
	 * mutator method for this companion plant 2 id
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

	/**
	 * insert a new companion plant relationship ito mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function insert(\PDO $pdo) {

		//create query template
		$query = "INSERT INTO companionPlant(companionPlant1Id, companionPlant2Id) VALUES (:companionPlant1Id, :companionPlant2Id)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$parameters = ["companionPlant1Id"=>$this->companionPlant1Id, "companionPlant2Id"=>$this->companionPlant2Id];
		$statement->execute($parameters);

	}

	/**
	 * delete a companion plant from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError i $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// create query template
		// note: need to check both cases: companionPlant1Id, companionPlant2Id, and companionPlant2Id, companion1Id since order does not matter
		$query ="DELETE FROM garden WHERE ((companionPlant1Id= :companionPlant1Id AND companionPlant2Id= :companionPlant2Id) OR (companionPlant1Id = :companionPlant2Id AND companionPlant2Id = :companionPlant1Id))";
		$statement = $pdo->prepare($query);

		//bind parameters
		$parameters = ["companionPlant1Id" =>$this->companionPlant1Id, "companionPlant2Id"=>$this->companionPlant2Id];
		$statement->execute($parameters);
	}

/**
 * get a single companion plant entry by specifying BOTH plant ids.
 *  order of plant ids does not matter
 *
 * @param \PDO $pdo a PDO connection object
 * @param int $plant1Id a valid plant id
 * @param int $plant2Id a valid plant id
 * @return CompanionPlant|null return a CompanionPlant that has both specific plant ids (in any order), or none if there is not companion plant entry that has BOTH of the specified plant ids.
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if the parameters don't match the type hints
 **/
public static function getCompanionPlantByBothPlantIds(\PDO $pdo, int $plant1Id, int $plant2Id) {
	if($plant1Id <=0 || $plant1Id <=0) {
		throw(new\RangeException("companion plant id must be positive"));
	}

		//create query template
	$query = "SELECT companionPlant1Id, companionPlant2Id FROM companionPlant WHERE ((companionPlant1Id = :plant1Id, companionPlant2Id = :plant2Id) OR (companionplant1Id = :plant2Id, companionPlant2Id = :plant2Id))";
		$statement = $pdo->prepare($query);

		//bind parameters
		$parameters = ["plant1Id"=>$plant1Id, "plant2Id"=>$plant2Id];
		$statement->execute($parameters);

		// get result from mySQL
		try {
			$companionPlant = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch ();
			if($row !== false) {
				$companionPlant = new CompanionPlant ($row["companionPlant1Id"], $row["companionPlant2Id"]);
			}
		} catch(\Exception $exception) {
			// if row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return $companionPlant;
}
	/**
	 * Get all the Companion Plant entries that have the specified Plant Id.
	 *
	 * @param \PDO $pdo the PDO connection object.
	 * @param int $plantId the ID of the plant we are searching for.
	 * @return \SplFixedArray of SplFixedArray of Companion Plants or null if no matches found.
	 * @throws \PDOException for mySQL related errors
	 * @throws \TypeError if variables are not the correct data type.
	 **/
	public static function getCompanionPlantsByPlantId(\PDO $pdo, int $plantId) {
		if($plantId <= 0) {
			throw(new \RangeException("companion plant id must be postive"));
		}

		// create query template
		$query = "SELECT companionPlant1Id, companionPlant2Id FROM companionPlant WHERE ((companionPlant1Id = :plantId) OR (companionPlant2Id = :plantId))";
		$statement = $pdo->prepare($query);

		// bind parameters
		$parameters = ["plantId"=>$plantId];
		$statement->execute($parameters);

		//build an array of companionPlants
		$companionPlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row=$statement->fetch()) !== false){
			try {
				$companionPlant = new CompanionPlant ($row["companionPlant1Id"], $row["companionPlant2Id"]);
				$companionPlants[$companionPlants->key()]=$companionPlant;
				$companionPlants->next();
			}catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($companionPlants);
	}

	/**
	 * Gets all Companion Plants
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of companion plants found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throw \TypeError if $pdo is not a PDO conneciton object.
	 **/
	public static function getAllCompanionPlants(\PDO $pdo) {

		// create query template
		$query = "SELECT companionPlant1Id, CompanionPlant2Id FROM companionPlant";
		$statement = $pdo->prepare($query);
		$statement ->execute();

		// build an array of CompanionPlants
		$companionPlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !==false) {
		try{
			$companionPlant = new companionPlant ($row["companionPlant1Id"], $row["companionPlant2Id"]);
			$companionPlants[$companionPlants->key()] = $companionPlant;
			$companionPlants->next();
		} catch(\Exception $exception){
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($companionPlants);
	}
}