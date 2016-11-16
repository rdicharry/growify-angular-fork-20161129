<?php
namespace Edu\Cnm\Growify;

require_once("autoload.php");


/**
 * Creating class for CompanionPlant
 *
 * This is the class for the CompanionPlant for the Growify capstone.
 *
 * @author Ana Vela <avela7@cnm.edu>
 * @version 1.0
 **/

class CompanionPlant implements \JsonSerializable{
/**
 *
 *  id for first CompanionPlant - foreign key
 * @var int $companionPlant1Id
 **/
	private $companionPlant1Id;

	/**
	 *
	 * id for second CompanionPlant - foreign key
	 * @var int $companionPlant2Id
	 *
	 **/

	private $companionPlant2Id;

	/**
	 * constructor for this CompanionPlant
	 *
	 * @param int $newCompanionPlant1Id
	 * @param int $newCompanionPlant2Id
	 * @throws \RangeException if data values are out of bounds (e.g. negative values for plant ids
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if data types violate type hints
	 * @internal param int|null $companionPlant1Id first CompanionPlant
	 * @internal param int|null $companionPlant2Id second CompanionPlant
	 *
	 **/
	public function __construct(int $newCompanionPlant1Id, int $newCompanionPlant2Id) {
		try {
			$this->setCompanionPlant1Id($newCompanionPlant1Id);
			$this->setCompanionPlant2Id($newCompanionPlant2Id);

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
	 * @return int value of companion plant 1 id
	 **/
	public function getCompanionPlant1Id(): int {
		return ($this->companionPlant1Id);
	}

	/**
	 * mutator method for this companion plant 1 id
	 *
	 * @param int|null $newCompanionPlant1Id new value of companion plant 1 id
	 * @throws \RangeException if $newCompanionPlant1Id is not positive
	 * @throws \TypeError if $newCompanionPlant1Id is not an integer
	 **/
	public function setCompanionPlant1Id (int $newCompanionPlant1Id) {
		// verify the companion plant 1 is positive
		if($newCompanionPlant1Id <= 0) {
			throw(new \RangeException("companion plant  1is not a positive"));
		}
		// convert and store the companion plant 1 id
		$this->companionPlant1Id =$newCompanionPlant1Id;
	}
	/**
	 * accessor method for companion plant 2 id
	 *
	 * @return int value for companion plant 2 id
	 **/
	public function getCompanionPlant2Id(): int {
		return $this->companionPlant2Id;
	}
	/**
	 * mutator method for this companion plant 2 id
	 *
	 * @param int|null $newCompanionPlant2Id new value of companion plant 2
	 * @throws \RangeException if $newCompanionPlant2Id is not positive
	 * @throws \TypeError if $newCompanionPlant2Id is not an integer
	 *
	 **/

	public function setCompanionPlant2Id (int $newCompanionPlant2Id) {
		// verify the companion plant 2 is positive
		if($newCompanionPlant2Id <= 0) {
			throw(new \RangeException("companion plant is not a positive"));
	}
	 // convert and store the companion plant 2
		$this->companionPlant2Id = $newCompanionPlant2Id;
	}

	/**
	 * check whether a mySQL entry for a given pair of plant ids already exists in the table.
	 * @param \PDO $pdo a PDO connection object
	 * @param int $companionPlant1Id a valid plant id
	 * @param int $companionPlant2Id a valid plant id
	 * @return bool true if the entry already exists in mySQL, false if it doesn't
	 **/
	public static function existsCompanionPlantEntry(\PDO $pdo, int $companionPlant1Id, int $companionPlant2Id) {
		// first check if this will create a duplicate DB entry
		$query = "SELECT companionPlant1Id, companionPlant2Id FROM companionPlant WHERE (companionPlant1Id = :companionPlant1Id AND companionPlant2Id = :companionPlant2Id) OR (companionPlant1Id = :companionPlant2Id AND companionPlant2Id = :companionPlant1Id)";
		$parameters = ["companionPlant1Id"=>$companionPlant1Id, "companionPlant2Id"=>$companionPlant2Id];
		$statement = $pdo->prepare($query);
		$statement->execute($parameters);

		if($statement->rowCount() > 0) {
			return true;
		}
		return false;
	}


	/**
	 * insert a new companion plant relationship ito mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function insert(\PDO $pdo) {

		if(CompanionPlant::existsCompanionPlantEntry($pdo, $this->companionPlant1Id, $this->companionPlant2Id)===false) {
			// bind the member variables to the place holders in the template
			$parameters = ["companionPlant1Id" => $this->companionPlant1Id, "companionPlant2Id" => $this->companionPlant2Id];

			//create query template
			$insertQuery = "INSERT INTO companionPlant(companionPlant1Id, companionPlant2Id) VALUES (:companionPlant1Id, :companionPlant2Id)";
			$insertStatement = $pdo->prepare($insertQuery);

			//bind the member variables to the place holders in the template
			$insertStatement->execute($parameters);
		} else {
			throw(new \PDOException("cannot insert duplicate companion plant entry"));
		}
	}

	/**
	 * delete a companion plant from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError i $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		// first check if the entry exists in order to delete , throw an error otherwise
		if(CompanionPlant::existsCompanionPlantEntry($pdo, $this->companionPlant1Id, $this->companionPlant2Id) === false){
			throw new \PDOException("cannot delete an entry that does not exist");
		}

		// bind parameters
		$parameters = ["companionPlant1Id" => $this->companionPlant1Id, "companionPlant2Id" => $this->companionPlant2Id];

		// create query template and execute
		$query = "DELETE FROM companionPlant WHERE (companionPlant1Id  = :companionPlant1Id) AND (companionPlant2Id = :companionPlant2Id)";
		$statement = $pdo->prepare($query);
		$statement->execute($parameters);

		// switch order of parameters input int mySQL, and run the new query
		$query = "DELETE FROM companionPlant WHERE (companionPlant1Id = :companionPlant2Id) AND (companionPlant2Id =:companionPlant1Id)";
		$statement = $pdo->prepare($query);
		$statement->execute($parameters);
	}





	/**
	 * Gets all Companion Plant entries that have the specified plant id.
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $plantId of the plant we are searching for.
	 * @return \SplFixedArray SplFixedArray of companion plants found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throw \TypeError if $pdo is not a PDO conneciton object.
	 **/
	public static function getAllCompanionPlantsByPlantId(\PDO $pdo, int $plantId){
		if($plantId <= 0){
		throw(new \RangeException("companion plant id must be positive"));
}
		// create query template
		$query = "SELECT companionPlant1Id, companionPlant2Id FROM companionPlant WHERE ((companionPlant1Id = :plantId) OR (companionPlant2Id=:plantId))";
		$statement = $pdo->prepare($query);

		//bind parameters
		$parameters = ["plantId"=>$plantId];
		$statement->execute($parameters);

		// build an array of CompanionPlants
		$companionPlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row=$statement->fetch()) !==false){
		try{
			$companionPlant = new CompanionPlant ($row["companionPlant1Id"], $row["companionPlant2Id"]);
			$companionPlants[$companionPlants->key()]=$companionPlant;
			$companionPlants->next();
		}catch(\Exception $exception){
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($companionPlants);
	}




/**
 * get all companion plants
 * @param \PDO $pdo PDO connection object
 * @return \SplFixedArray SplFixedArray of companion plants found or null if none found
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $do is not a PDO connection object.
 **/

public static function getAllCompanionPlants(\PDO $pdo) {

	// create query template
	$query = "SELECT companionPlant1Id, companionPlant2Id FROM companionPlant";
	$statement = $pdo->prepare($query);
	$statement->execute();

	//build an array of companionPlants
	$companionPlants = new \SplFixedArray($statement->rowCount());
	$statement->setFetchMode(\PDO::FETCH_ASSOC);

	while (($row = $statement->fetch()) !=false) {
		try {
			$companionPlant = new CompanionPlant ($row["companionPlant1Id"], $row["companionPlant2Id"]);
			$companionPlants[$companionPlants->key()] = $companionPlant;
			$companionPlants->next();
		} catch(\Exception $exception){
			//if the row couldn't be converted, rethrow it
			throw (new \PDOException($exception->getMessage(), 0, $exception));
		}
	}
	return ($companionPlants);
}
	/**
	 * format state variables for JSON serialization
	 * @return array an array with serialized state variables
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}