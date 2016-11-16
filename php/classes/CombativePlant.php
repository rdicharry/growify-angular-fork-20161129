<?php
namespace Edu\Cnm\Growify;

require_once("autoload.php");

/**
 * Class CombativePlant represents a pair of plants that DO NOT grow well together.
 * @author Rebecca Dicharry <rdicharry@cnm.edu>
 *
 */
class CombativePlant implements \JsonSerializable{

	/**
	 * id of one combative plant - this is a foreign key
	 * @var int $combativePlant1Id;
	 */
	private $combativePlant1Id;

	/**
	 * id of another combative plant - this is a foreign key
	 * @var int $combativePlant2Id
	 */
	private $combativePlant2Id;

	/**
	 * CombativePlant constructor.
	 * @param int $newCombativePlant1Id
	 * @param int $newCombativePlant2Id

	 * @throws \TypeError if parameters violate type hints
	 * @throws \RangeException if data values are out of bounds (e.g. negative values for plant ids
	 * @throws \Exception if some other exception occurs
	 *
	 */
	public function __construct(int $newCombativePlant1Id, int $newCombativePlant2Id){
		try{
			$this->setCombativePlant1Id($newCombativePlant1Id);
			$this->setCombativePlant2Id($newCombativePlant2Id);
		} //rethrow to caller
		 catch(\RangeException $range){
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError){
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception){
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor method for combativePlant1
	 * @return int $combativePlant1 a plant id
	 */
	public function getCombativePlant1Id(): int {
		return $this->combativePlant1Id;
	}

	/**
	 * Mutator method for combativePlant1Id.
	 * @param int $newCombativePlant
	 * @throws \RangeException if $newCombativePlant is not positive.
	 * @throws \TypeError if $newCombativePlant is not an int.
	 */
	public function setCombativePlant1Id(int $newCombativePlant) {
		if($newCombativePlant <= 0){
			throw(new \RangeException("combative plant id must be positive"));
		}
		$this->combativePlant1Id = $newCombativePlant;
	}

	/**
	 * Accessor method for combativePlant2Id
	 * @return int $combativeplant2Id a plant id.
	 */
	public function getCombativePlant2Id(): int {
		return $this->combativePlant2Id;
	}

	/**
	 * Mutator method for combativePlant2Id
	 * @param int $newCombativePlant
	 * @throws \RangeException if $newCombativePlant is not positive
	 * @throws \TypeError if $newCombativePlant is not an int.
	 */
	public function setCombativePlant2Id(int $newCombativePlant) {

		if($newCombativePlant <= 0){
			throw (new \RangeException("combative plant id must be positive"));
		}

		$this->combativePlant2Id = $newCombativePlant;
	}

	/**
	 * check whethere a mySQL entry for a given pair of plant Ids already exists in the table.
	 * @param \PDO $pdo a PDO connection object
	 * @param int $combativePlant1Id a valid plant id
	 * @param int $combativePlant2Id a valid plant id.
	 * @return bool true if the entry already exists in mySQL, false if it doesn't
	 */
	public static function existsCombativePlantEntry(\PDO $pdo, int $combativePlant1Id, int $combativePlant2Id){
		// first check if this will create a duplicate DB entry
		$query = "SELECT combativePlant1Id, combativePlant2Id FROM combativePlant WHERE 
(combativePlant1Id = :combativePlant1Id AND combativePlant2Id = :combativePlant2Id) OR 
(combativePlant1Id = :combativePlant2Id AND combativePlant2Id = :combativePlant1Id)";
		$parameters = ["combativePlant1Id"=>$combativePlant1Id, "combativePlant2Id"=>$combativePlant2Id];
		$statement = $pdo->prepare($query);
		$statement->execute($parameters);

		if($statement->rowCount() > 0){
			return true;
		}
		return false;
	}

	/**
	 * insert a new combative plant relationship into mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function insert(\PDO $pdo){

		if(CombativePlant::existsCombativePlantEntry($pdo, $this->combativePlant1Id, $this->combativePlant2Id)===false){
			//bind the member variables to the place holders in the template
			$parameters = ["combativePlant1Id"=>$this->combativePlant1Id, "combativePlant2Id"=>$this->combativePlant2Id];

			//create query template
			$insertQuery = "INSERT INTO combativePlant(combativePlant1Id, combativePlant2Id) VALUES (:combativePlant1Id, :combativePlant2Id )";
			$insertStatement = $pdo->prepare($insertQuery);

			// bind the member variables to the place holders in the template
			$insertStatement->execute($parameters);
		} else {
			throw(new \PDOException("cannot insert duplicate combative plant entry"));
		}

	}

	/**
	 * delete mySQL entry corresponding to this combative plant entry
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function delete(\PDO $pdo){
		/// first check if the entry exists, if not, throw an exception
		if(CombativePlant::existsCombativePlantEntry($pdo, $this->combativePlant1Id, $this->combativePlant2Id) === false){
			throw new \PDOException("cannot delete an entry that does not exist");
		}

		// bind parameters
		$parameters = ["combativePlant1Id"=>$this->combativePlant1Id, "combativePlant2Id"=>$this->combativePlant2Id];

		// create query template
		$query = "DELETE FROM combativePlant WHERE (combativePlant1Id  = :combativePlant1Id) AND (combativePlant2Id = :combativePlant2Id)";
		$statement = $pdo->prepare($query);

		// execute statement
		$statement->execute($parameters);

		// switch order of parameters input into mySQL, and run new query
		$query = "DELETE FROM combativePlant WHERE (combativePlant1Id  = :combativePlant2Id) AND ( combativePlant2Id = :combativePlant1Id)";
		$statement = $pdo->prepare($query);

		// execute statement
		$statement->execute($parameters);
	}

/*	no update for this object - we do not have a use case for it. public function update(\PDO $pdo){	}*/



	/**
	 * Get all of the Combative Plant entries that have the specified plant Id.
	 * @param \PDO $pdo the PDO connection object.
	 * @param int $plantId the Id of the plant we are searching for.
	 * @return \SplFixedArray SplFixedArray of Combative Plants, or null if no matches found.
	 * @throws \PDOException for mySQL related errors
	 * @throws \TypeError if variables are not the correct data type.
	 */
	public static function getCombativePlantsByPlantId(\PDO $pdo, int $plantId){
		if($plantId <= 0){
			throw(new \RangeException("combative plant id must be positive"));
		}

		// create query template
		$query = "SELECT combativePlant1Id, combativePlant2Id FROM combativePlant WHERE ((combativePlant1Id = :plantId ) OR (combativePlant2Id=:plantId))";
		$statement = $pdo->prepare($query);

		// bind parameters
		$parameters = ["plantId"=>$plantId];
		$statement->execute($parameters);

		// build an array of combativePlants
		$combativePlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row=$statement->fetch()) !== false){
			try{
				$combativePlant = new CombativePlant($row["combativePlant1Id"], $row["combativePlant2Id"]);
				$combativePlants[$combativePlants->key()]=$combativePlant;
				$combativePlants->next();
			}catch(\Exception $exception){
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($combativePlants);

	}

	/**
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of combative plants found or null if none found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public static function getAllCombativePlants(\PDO $pdo){

		// create query template
		$query = "SELECT combativePlant1Id, combativePlant2Id FROM combativePlant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of combativePlants
		$combativePlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch())!==false){
			try{
				$combativePlant = new CombativePlant($row["combativePlant1Id"], $row["combativePlant2Id"]);
				$combativePlants[$combativePlants->key()] = $combativePlant;
				$combativePlants->next();
			} catch(\Exception $exception){
				// rethrow if the row couldn't be converted'
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return($combativePlants);


	}

	/**
	 * format state variables for JSON serialization
	 * @return array an array with serialized state variables
	 */
	public function jsonSerialize(){
		$fields = get_object_vars($this);
		return($fields);
	}

}