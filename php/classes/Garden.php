<?php
namespace Edu\Cnm\Growify;

require_once("autoload.php");

class Garden implements \JsonSerializable {
	use ValidateDate;

	/**
	 * the id of the User who "Owns" this garden
	 * @var int $gardenProfileId
	 */
	private $gardenProfileId;

	/**
	 * the (user entered) date and time the Garden was planted
	 * @var \DateTime $gardenPlantId
	 */
	private $gardenDatePlanted;

	/**
	 * The Id of the Plant for this garden entry.
	 * @var int $gardenPlantId
	 */
	private $gardenPlantId;

	/**
	 * Garden constructor.
	 * @param int $newGardenProfileId required Id of the profile user who "owns" this garden.
	 * @param \DateTime $newGardenDatePlanted User-entered planting date for the plant being added to the garden.
	 * @param int $newGardenPlantId the Plant being added to the user's garden.
	 * @throws \InvalidArgumentException if $newGardenDatePlanted is not a DateTime object.
	 * @throws \RangeException if $newGardenProfileId or $newGardenPlantId are not valid (positive), or if $newGardenDatePlanted is not a valid date.
	 * @throws \TypeError if the data types violate type hints
	 * @throws \Exception if some other exception occurs.
	 */
	public function __construct(int $newGardenProfileId, \DateTime $newGardenDatePlanted, int $newGardenPlantId){

		try{
			$this->setGardenProfileId($newGardenProfileId);
			$this->setGardenDatePlanted($newGardenDatePlanted);
			$this->setGardenPlantId($newGardenPlantId);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError){
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception){
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}

	}

	/**
	 * Accessor method for gardenProfileId.
	 * @return int profileId of user who owns this garden.
	 */
	public function getGardenProfileId(){
		return($this->gardenProfileId);
	}

	/**
	 * Mutator method for gardenProfileId.
	 * @param int $newGardenProfileId
	 * @throws \RangeException if the $newGardenProfileId is not positive.
	 * @throws \TypeError if $newGardenProfileId does not represent an int.
	 */
	public function setGardenProfileId(int $newGardenProfileId){
		// verify the profile id is positive
		if($newGardenProfileId <= 0){
			throw(new \RangeException("Garden profile id must be positive."));
		}

		//convert and store the profile id
		$this->gardenProfileId = $newGardenProfileId;
	}

	/**
	 * Accessor method for gardenDatePlanted.
	 * @return \DateTime user-entered date the garden was planted.
	 */
	public function getGardenDatePlanted(){
		return($this->gardenDatePlanted);
	}

	/**
	 * Mutator method for gardenDatePlanted.
	 * @param \DateTime $newGardenDatePlanted user-entered date that the Plant was planted in this Garden.
	 * @throws \InvalidArgumentException if $newGardenDatePlanted is not a valid object.
	 * @throws \RangeException if the date is not a valid date.
	 */
	public function setGardenDatePlanted(\DateTime $newGardenDatePlanted){
		// store the date
		try{
			$newGardenDatePlanted = self::validateDateTime($newGardenDatePlanted);
		} catch(\InvalidArgumentException $invalidArgument){
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range){
			throw(new \RangeException($range->getMessage(), 0, $range));
		}
		$this->gardenDatePlanted = $newGardenDatePlanted;
	}

	/**
	 * Accessor method for gardenPlantId.
	 * @return int the PlantId of the Plant being added to the garden.
	 */
	public function getGardenPlantId(){
		return($this->gardenPlantId);
	}

	/**
	 * Mutator method for gardenPlantId.
	 * @param int $newGardenPlantId the Id of the Plant being added to the Garden.
	 */
	public function setGardenPlantId(int $newGardenPlantId){
		// verify the plantId is positive
		if($newGardenPlantId <= 0){
			throw(new \RangeException("Plant id must be positive."));
		}

		// store
		$this->gardenPlantId = $newGardenPlantId;
	}

	/**
	 * Insert a new Garden entry.
	 * @param \PDO $pdo the PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function insert(\PDO $pdo){

		//create query template
		$query = "INSERT INTO garden(gardenProfileId, gardenDatePlanted, gardenPlantId) VALUES (:gardenProfileId, :gardenDatePlanted, :gardenPlantId)";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders in the template
		// note: do not need to preserve any time information (there should not be any) as we are only interested in the planting date
		$formattedDate = $this->gardenDatePlanted->format("Y-m-d");
		$parameters = ["gardenProfileId"=>$this->gardenProfileId, "gardenDatePlanted"=>$formattedDate, "gardenPlantId"=>$this->gardenPlantId];
		$statement->execute($parameters);

	}

	/**
	 * Delete a Garden entry. This effective deletes one Plant from the collection that is
	 * a user's garden. To delete an entire garden for a user, you must delete ALL garden
	 * entries for that user.
	 * @param \PDO $pdo PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO object.
	 */
	public function delete(\PDO $pdo){

		// create query template
		$query = "DELETE FROM garden WHERE gardenProfileId = :gardenProfileId AND gardenPlantId = :gardenPlantId";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholder in template
		$parameters = ["gardenProfileId"=>$this->gardenProfileId, "gardenPlantId"=>$this->gardenPlantId];
		$statement->execute($parameters);
	}

	/**
	 * Updates the garden plant entry in mySQL. This method can effectively ONLY UPDATE THE DATE PLANTED. Changing the plantId would require deleting the old entry and creating a new one.
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 */
	public function update(\PDO $pdo){
		//create query template
		$query = "UPDATE garden SET gardenDatePlanted = :gardenDatePlanted WHERE gardenProfileId = :gardenProfileId AND gardenPlantId = :gardenPlantId";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders
		$formattedDate = $this->gardenDatePlanted->format("Y-m-d");
		$parameters = ["gardenDatePlanted"=>$formattedDate, "gardenProfileId"=>$this->gardenProfileId, "gardenPlantId"=>$this->gardenPlantId];
		$statement->execute($parameters);
	}

	public function getGardenByGardenProfileId(\PDO $pdo, int $gardenProfileId){
		// could return many values (an array of garden entries
	}

	public function getAllGardens(\PDO $pdo){

	}

}