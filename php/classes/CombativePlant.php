<?php
namespace Edu\Cnm\Growify;

require_once("autoload.php");

/**
 * Class CombativePlant represents a pair of plants that DO NOT grow well together.
 * @author Rebecca Dicharry <rdicharry@cnm.edu>
 *
 */
class CombativePlant {

	/**
	 * id of one combative plant - this is a foreign key
	 * @var int $combativePlant1Id;
	 */
	private $combativePlant1;

	/**
	 * id of another combative plant - this is a foreign key
	 * @var int $combativePlant2Id
	 */
	private $combativePlant2;

	/**
	 * CombativePlant constructor.
	 * @param int $newCombativePlant1
	 * @param int $newCombativePlant2

	 * @throws \TypeError if parameters violate type hints
	 * @throws \RangeException if data values are out of bounds (e.g. negative values for plant ids
	 * @throws \Exception if some other exception occurs
	 *
	 */
	public function __construct(int $newCombativePlant1, int $newCombativePlant2){
		try{
			$this->setCombativePlant1($newCombativePlant1);
			$this->setCombativePlant2($newCombativePlant2);
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
	public function getCombativePlant1(): int {
		return $this->combativePlant1;
	}

	/**
	 * Mutator method for combativePlant1.
	 * @param int $newCombativePlant
	 * @throws \RangeException if $newCombativePlant is not positive.
	 * @throws \TypeError if $newCombativePlant is not an int.
	 */
	public function setCombativePlant1(int $newCombativePlant) {
		if($newCombativePlant <= 0){
			throw(new \RangeException("combative plant id must be positive"));
		}
		$this->combativePlant1 = $newCombativePlant;
	}

	/**
	 * Accessor method for combativePlant2
	 * @return int $combativeplant2 a plant id.
	 */
	public function getCombativePlant2(): int {
		return $this->combativePlant2;
	}

	/**
	 * Mutator method for combativePlant2
	 * @param int $newCombativePlant
	 * @throws \RangeException if $newCombativePlant is not positive
	 * @throws \TypeError if $newCombativePlant is not an int.
	 */
	public function setCombativePlant2(int $newCombativePlant) {

		if($newCombativePlant <= 0){
			throw new(\RangeException("combative plant id must be positive"));
		}

		$this->combativePlant2 = $newCombativePlant;
	}

	/**
	 * insert a new combative plant relationship into mySQL
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function insert(\PDO $pdo){

		//create query template
		$query = "INSERT INTO combativePlant(combativePlant1, combativePlant2) VALUES (:combativePlant1, :combativePlant2 )";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$parameters = ["combativePlant1"=>$this->combativePlant1, "combativePlant2"=>$this->combativePlant2];
		$statement->execute($parameters);

	}

	/**
	 * delete mySQL entry corresponding to this combative plant entry
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException if mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function delete(\PDO $pdo){

		// create query template
		// note: need to check both cases: combativeplant1, combativeplant2 AND combativeplant2, combativeplant1 since order does not matter
		// TODO ensure that we have done this check in companion plant as well!
		$query = "DELETE FROM garden WHERE ((combativePlant1= :combativePlant1 AND combativePlant2= :combativePlant2) OR (combativePlant1 = :combativePlant2 AND combativePlant2 = :combativePlant1))";
		$statement = $pdo->prepare($query);

		// bind parameters
		$parameters = ["combativePlant1"=>$this->combativePlant1, "combativePlant2"=>$this->combativePlant2];
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
		$query = "SELECT combativePlant1, combativePlant2 FROM combativePlant WHERE ((combativePlant1 = :plantId ) OR (combativePlant2=:plantId))";
		$statement = $pdo->prepare($query);

		// bind parameters
		$parameters = ["plantId"=>$plantId];
		$statement->execute($parameters);

		// build an array of combativePlants
		$combativePlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);

		while(($row=$statement->fetch()) !== false){
			try{
				$combativePlant = new CombativePLant($row["combativePlant1"], $row["combativePlant2"]);
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
		$query = "SELECT combativePlant1, combativePlant2 FROM combativePlant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of combativePlants
		$combativePlants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch())!==false){
			try{
				$combativePlant = new CombativePlant($row["combativePlant1"], $row["combativePlant2"]);
				$combativePlants[$combativePlants->key()] = $combativePlant;
				$combativePlants->next();
			} catch(\Exception $exception){
				// rethrow if the row couldn't be converted'
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}

		return($combativePlants);


	}

}