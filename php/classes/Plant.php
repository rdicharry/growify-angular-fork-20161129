<?php

namespace Edu\Cnm\Growify;

require_once("autoload.php");

/**
 * Plant Information Class
 *
 * This Plant will access and store data.
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class Plant {
	/**
	 * id for this plant; this is the primary key
	 * @var int $plantId
	 **/
	private $plantId;
	/**
	 * name of this plant
	 * @var string $plantName
	 **/
	private $plantName;
	/**
	 * variety of this plant
	 * @var string $plantVariety
	 **/
	private $plantVariety;
	/**
	 * description of this plant
	 * @var string $plantDescription
	 **/
	private $plantDescription;
	/**
	 * type of plant
	 * @var string $plantType
	 **/
	private $plantType;
	/**
	 * planting distance between this plant and others (in feet)
	 * @var float $plantSpread
	 **/
	private $plantSpread;
	/**
	 * amount of days before this plant should be harvested
	 * @var int plantDaysToHarvest
	 **/
	private $plantDaysToHarvest;
	/**
	 * average mature height for this plant
	 * @var float plantHeight
	 **/
	private $plantHeight;
	/**
	 * minimum growing temperature for this plant
	 * @var int plantMinTemp
	 **/
	private $plantMinTemp;
	/**
	 * maximum growing temperature for this plant
	 * @var int plantMaxTemp
	 **/
	private $plantMaxTemp;
	/**
	 * soil moisture needs for this plant
	 * @var string plantSoilMoisture
	 **/
	private $plantSoilMoisture;

	/**
	 * @param int|null $newPlantId
	 * @param string $newPlantName
	 * @param string $newPlantVariety
	 * @param string $newPlantDescription
	 * @param string $newPlantType
	 * @param float $newPlantSpread
	 * @param int $newPlantDaysToHarvest
	 * @param float $newPlantHeight
	 * @param int $newPlantMinTemp
	 * @param int $newPlantMaxTemp
	 * @param string $newPlantSoilMoisture
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type
	 * @throws \Exception for other exceptions
	 **/
	public function __construct($newPlantId, $newPlantName, $newPlantVariety, $newPlantDescription, $newPlantType, $newPlantSpread, $newPlantDaysToHarvest, $newPlantHeight, $newPlantMinTemp, $newPlantMaxTemp, $newPlantSoilMoisture) {
		try {
			$this->setPlantId($newPlantId);
			$this->setPlantName($newPlantName);
			$this->setPlantVariety($newPlantVariety);
			$this->setPlantDescription($newPlantDescription);
			$this->setPlantType($newPlantType);
			$this->setPlantSpread($newPlantSpread);
			$this->setPlantDaysToHarvest($newPlantDaysToHarvest);
			$this->setPlantHeight($newPlantHeight);
			$this->setPlantMinTemp($newPlantMinTemp);
			$this->setPlantMaxTemp($newPlantMaxTemp);
			$this->setPlantSoilMoisture($newPlantSoilMoisture);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for plantId
	 * @return int
	 **/
	public function getPlantId() {
		return $this->plantId;
	}

	/**
	 * accessor method for plantName
	 * @return string
	 **/
	public function getPlantName() {
		return $this->plantName;
	}

	/**
	 * accessor method for plantVariety
	 * @return string
	 **/
	public function getPlantVariety() {
		return $this->plantVariety;
	}

	/**
	 * accessor method for plantDescription
	 * @return string
	 **/
	public function getPlantDescription() {
		return $this->plantDescription;
	}

	/**
	 * accessor method for plantType
	 * @return string
	 **/
	public function getPlantType() {
		return $this->plantType;
	}

	/**
	 * accessor method for plantSpread
	 * @return float
	 **/
	public function getPlantSpread() {
		return $this->plantSpread;
	}

	/**
	 * accessor method for plantDaysToHarvest
	 * @return int
	 **/
	public function getPlantDaysToHarvest() {
		return $this->plantDaysToHarvest;
	}

	/**
	 * accessor method for plantHeight
	 * @return float
	 **/
	public function getPlantHeight() {
		return $this->plantHeight;
	}

	/**
	 * accessor method for plantMinTemp
	 * @return int
	 **/
	public function getPlantMinTemp() {
		return $this->plantMinTemp;
	}

	/**
	 * accessor method for plantMaxTemp
	 * @return int
	 **/
	public function getPlantMaxTemp() {
		return $this->plantMaxTemp;
	}

	/**
	 * accessor method for plantSoilMoisture
	 * @return string
	 **/
	public function getPlantSoilMoisture() {
		return $this->plantSoilMoisture;
	}

	/**
	 * mutator method for plantId
	 * @param int|null $newPlantId new value of plant id
	 * @throws \RangeException if $newPlantId is negative
	 * @throws \TypeError if $newPlantId is not an integer
	 **/
	public function setPlantId(int $newPlantId = null) {
		// if the plant id is null, this is a new plant without an id from mySQL
		if($newPlantId === null) {
			$this->plantId = null;
			return;
		}
		// verify that plant id is positive
		if($newPlantId <= 0) {
			throw (new \RangeException("plant id is not positive"));
		}
		$this->plantId = $newPlantId;
	}

	/**
	 * mutator method for plantName
	 * @param string $newPlantName new value of plant name
	 * @throws \InvalidArgumentException if $newPlantName has invalid contents or is empty
	 * @throws \RangeException if $newPlantName is too long
	 **/
	public function setPlantName(string $newPlantName) {
		$newPlantName = trim($newPlantName);
		$newPlantName = filter_var($newPlantName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlantName)) {
			throw (new \InvalidArgumentException("name is empty or has invalid contents"));
		}
		if(strlen($newPlantName) > 26) {
			throw(new \RangeException("name is too large"));
		}
		$this->plantName = $newPlantName;
	}

	/**
	 * mutator method for plantVariety
	 * @param string $newPlantVariety new value of plant variety
	 * @throws \InvalidArgumentException if $newPlantVariety has invalid contents or is empty
	 * @throws \RangeException if $newPlantVariety is too long
	 **/
	public function setPlantVariety($newPlantVariety) {
		$newPlantVariety = trim($newPlantVariety);
		$newPlantVariety = filter_var($newPlantVariety, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlantVariety)) {
			throw (new \InvalidArgumentException("variety is empty or has invalid contents"));
		}
		if(strlen($newPlantVariety) > 26) {
			throw(new \RangeException("variety is too large"));
		}
		$this->plantVariety = $newPlantVariety;
	}

	/**
	 * mutator method for plantDescription
	 * @param string $newPlantDescription new value of plant description
	 * @throws \InvalidArgumentException if $newPlantDescription has invalid contents or is empty
	 * @throws \RangeException if $newPlantDescription is too long
	 **/
	public function setPlantDescription($newPlantDescription) {
		$newPlantDescription = trim($newPlantDescription);
		$newPlantDescription = filter_var($newPlantDescription, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlantDescription)) {
			throw (new \InvalidArgumentException("description is empty or has invalid contents"));
		}
		if(strlen($newPlantDescription) > 512) {
			throw(new \RangeException("description is too large"));
		}
		$this->plantDescription = $newPlantDescription;
	}

	/**
	 * mutator method for plantType
	 * @param string $newPlantType new value of plant type
	 * @throws \InvalidArgumentException if $newPlantType has invalid contents or is empty
	 * @throws \RangeException if $newPlantType is too long
	 **/
	public function setPlantType($newPlantType) {
		$newPlantType = trim($newPlantType);
		$newPlantType = filter_var($newPlantType, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlantType)) {
			throw (new \InvalidArgumentException("type is empty or has invalid contents"));
		}
		if(strlen($newPlantType) > 9) {
			throw(new \RangeException("type is too large"));
		}
		$this->plantType = $newPlantType;
	}

	/**
	 * mutator method for plantSpread
	 * @param float $newPlantSpread new value of plant spread
	 * @throws \UnexpectedValueException if $newPlantSpread is not a float
	 * @throws \RangeException if $newPlantSpread is negative
	 **/
	public function setPlantSpread($newPlantSpread) {
		$newPlantSpread = filter_var($newPlantSpread, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		if($newPlantSpread === false) {
			throw (new \UnexpectedValueException("spread is not a valid float"));
		}
		if($newPlantSpread >= 0) {
			throw (new \RangeException("spread is not positive"));
		}
		$this->plantSpread = $newPlantSpread;
	}

	/**
	 * mutator method for plantDaysToHarvest
	 * @param int $newPlantDaysToHarvest new value of plant days to harvest
	 * @throws \UnexpectedValueException if $newPlantDaysToHarvest is not an int
	 * @throws \RangeException if $newPlantDaysToHarvest is negative
	 **/
	public function setPlantDaysToHarvest($newPlantDaysToHarvest) {
		$newPlantDaysToHarvest = filter_var($newPlantDaysToHarvest, FILTER_VALIDATE_INT);
		if($newPlantDaysToHarvest === false) {
			throw (new \UnexpectedValueException("days to harvest is not a valid int"));
		}
		if($newPlantDaysToHarvest >= 0) {
			throw (new \RangeException("days to harvest is not positive"));
		}
		$this->plantDaysToHarvest = $newPlantDaysToHarvest;
	}

	/**
	 * mutator method for plantHeight
	 * @param float $newPlantHeight new value of plant mature height
	 * @throws \UnexpectedValueException if $newPlantHeight is not a float
	 * @throws \RangeException if $newPlantHeight is negative
	 **/
	public function setPlantHeight($newPlantHeight) {
		$newPlantHeight = filter_var($newPlantHeight, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		if($newPlantHeight === false) {
			throw (new \UnexpectedValueException("height is not a valid float"));
		}
		if($newPlantHeight >= 0) {
			throw (new \RangeException("height is not positive"));
		}
		$this->plantHeight = $newPlantHeight;
	}

	/**
	 * mutator method for plantMinTemp
	 * @param int $newPlantMinTemp new value of plant min temp
	 * @throws \UnexpectedValueException if $newPlantMinTemp is not a int
	 **/
	public function setPlantMinTemp($newPlantMinTemp) {
		$newPlantMinTemp = filter_var($newPlantMinTemp, FILTER_VALIDATE_INT);
		if($newPlantMinTemp === false) {
			throw (new \UnexpectedValueException("min temp is not a valid int"));
		}
		$this->plantMinTemp = $newPlantMinTemp;
	}

	/**
	 * mutator method for plantMaxTemp
	 * @param int $newPlantMaxTemp new value of plant max temp
	 * @throws \UnexpectedValueException if $newPlantMaxTemp is not a int
	 **/
	public function setPlantMaxTemp($newPlantMaxTemp) {
		$newPlantMaxTemp = filter_var($newPlantMaxTemp, FILTER_VALIDATE_INT);
		if($newPlantMaxTemp === false) {
			throw (new \UnexpectedValueException("max temp is not a valid int"));
		}
		$this->plantMaxTemp = $newPlantMaxTemp;
	}

	/**
	 * mutator method for plantSoilMoisture
	 * @param string $newPlantSoilMoisture new value of plant soil moisture
	 * @throws \InvalidArgumentException if $newPlantSoilMoisture has invalid contents or is empty
	 * @throws \RangeException if $newPlantSoilMoisture is too long
	 **/
	public function setPlantSoilMoisture($newPlantSoilMoisture) {
		$newPlantSoilMoisture = trim($newPlantSoilMoisture);
		$newPlantSoilMoisture = filter_var($newPlantSoilMoisture, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPlantSoilMoisture)) {
			throw (new \InvalidArgumentException("soil moisture is empty or has invalid contents"));
		}
		if(strlen($newPlantSoilMoisture) > 6) {
			throw(new \RangeException("soil moisture is too large"));
		}
		$this->plantSoilMoisture = $newPlantSoilMoisture;
	}

	/**
	 * Insert a new Plant entry.
	 * @param \PDO $pdo the PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function insert(\PDO $pdo) {
		//check to make sure this plant doesn't already exist
		if($this->plantId !== null) {
			throw(new \PDOException("not a new plant"));
		}

		//create query template
		$query = "INSERT INTO plant(plantId, plantName, plantVariety, plantDescription, plantType, plantSpread, plantDaysToHarvest, plantHeight, plantMinTemp, plantMaxTemp, plantSoilMoisture) VALUES (:plantId, :plantName, :plantVariety, :plantDescription, :plantType, :plantSpread, :plantDaysToHarvest, :plantHeight,:plantMinTemp,:plantMaxTemp, :plantSoilMoisture)";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders in the template
		$parameters = ["plantId" => $this->plantId, "plantName" => $this->plantName, "plantVariety" => $this->plantVariety, "plantDescription" => $this->plantDescription, "plantType" => $this->plantType, "plantSpread" => $this->plantSpread, "plantDaysToHarvest" => $this->plantDaysToHarvest, "plantHeight" => $this->plantHeight, "plantMinTemp" => $this->plantMinTemp, "plantMaxTemp" => $this->plantMaxTemp, "plantSoilMoisture" => $this->plantSoilMoisture];
		$statement->execute($parameters);

	}

	/**
	 * Delete a Plant entry.
	 * @param \PDO $pdo PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO object.
	 **/
	public function delete(\PDO $pdo) {
		// create query template
		$query = "DELETE FROM plant WHERE plantId = :plantId";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholder in template
		$parameters = ["plantId" => $this->plantId];
		$statement->execute($parameters);
	}

	/**
	 * Updates the Plant entry in mySQL.
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function update(\PDO $pdo) {
		//create query template
		$query = "UPDATE plant SET plantId = :plantId, plantName = :plantName, plantVariety = :plantVariety, plantDescription = :plantDescription, plantType = :plantType, plantSpread = :plantSpread, plantDaysToHarvest = :plantDaysToHarvest, plantHeight = :plantHeight, plantMinTemp = :plantMinTemp, plantMaxTemp = :plantMaxTemp, plantSoilMoisture = :plantSoilMoisture";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders
		$parameters = ["plantId" => $this->plantId, "plantName" => $this->plantName, "plantVariety" => $this->plantVariety, "plantDescription" => $this->plantDescription, "plantType" => $this->plantType, "plantSpread" => $this->plantSpread, "plantDaysToHarvest" => $this->plantDaysToHarvest, "plantHeight" => $this->plantHeight, "plantMinTemp" => $this->plantMinTemp, "plantMaxTemp" => $this->plantMaxTemp, "plantSoilMoisture" => $this->plantSoilMoisture];
		$statement->execute($parameters);
	}

	/**
	 * Get plant associated with the specified plant Id.
	 * @param \PDO $pdo a PDO connection object
	 * @param int $plantId a valid plant Id
	 * @return Plant|null Plant found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getPlantByPlantId(\PDO $pdo, int $plantId) {
		if($plantId <= 0) {
			throw(new \RangeException("Plant id must be positive."));
		}
		// create query template
		$query = "SELECT plantId, plantName, plantVariety, plantDescription, plantType, plantSpread, plantDaysToHarvest, plantHeight, plantMinTemp, plantMaxTemp, plantSoilMoisture FROM plant WHERE plantId= :plantId";
		$statement = $pdo->prepare($query);

		// bind the plant id to the place holder in the template
		$parameters = ["plantId" => $plantId];
		$statement->execute($parameters);

		// grab the plant from mySQL
		try {
			$plant = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$plant = new Plant($row["plantId"], $row["plantName"], $row["plantVariety"], $row["plantDescription"], $row["plantType"], $row["plantSpread"], $row["plantDaysToHarvest"], $row["plantHeight"], $row["plantMinTemp"], $row["plantMaxTemp"], $row["plantSoilMoisture"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($plant);
	}

	/**
	 * Get all plants associated with the specified plant name.
	 * @param \PDO $pdo a PDO connection object
	 * @param string $plantName name of plant being searched for
	 * @return \SplFixedArray SplFixedArray of Plants found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getPlantByPlantName(\PDO $pdo, string $plantName) {
		$plantName = trim($plantName);
		$plantName = filter_var($plantName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($plantName)) {
			throw (new \InvalidArgumentException("plant name is invalid"));
		}
		// create query template
		$query = "SELECT plantId, plantName, plantVariety, plantDescription, plantType, plantSpread, plantDaysToHarvest, plantHeight, plantMinTemp, plantMaxTemp, plantSoilMoisture FROM plant WHERE plantName LIKE :plantName";
		$statement = $pdo->prepare($query);

		// bind the plant name to the place holder in the template
		$parameters = ["plantName" => $plantName];
		$statement->execute($parameters);

		// build an array of plants
		$plants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false){
			try {
					$plant = new Plant($row["plantId"], $row["plantName"], $row["plantVariety"], $row["plantDescription"], $row["plantType"], $row["plantSpread"], $row["plantDaysToHarvest"], $row["plantHeight"], $row["plantMinTemp"], $row["plantMaxTemp"], $row["plantSoilMoisture"]);
				$plants[$plants->key()] = $plant;
				$plants->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($plants);
	}
	/**
	 * Get all plants associated with the specified plant type.
	 * @param \PDO $pdo a PDO connection object
	 * @param string $plantType type of plant being searched for
	 * @return \SplFixedArray SplFixedArray of Plants found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getPlantByPlantType(\PDO $pdo, string $plantType) {
		$plantType = trim($plantType);
		$plantType = filter_var($plantType, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($plantType)) {
			throw (new \InvalidArgumentException("plant type is invalid"));
		}
		// create query template
		$query = "SELECT plantId, plantName, plantVariety, plantDescription, plantType, plantSpread, plantDaysToHarvest, plantHeight, plantMinTemp, plantMaxTemp, plantSoilMoisture FROM plant WHERE plantType LIKE :plantType";
		$statement = $pdo->prepare($query);

		// bind the plant type to the place holder in the template
		$parameters = ["plantType" => $plantType];
		$statement->execute($parameters);

		// build an array of plants
		$plants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false){
			try {
				$plant = new Plant($row["plantId"], $row["plantName"], $row["plantVariety"], $row["plantDescription"], $row["plantType"], $row["plantSpread"], $row["plantDaysToHarvest"], $row["plantHeight"], $row["plantMinTemp"], $row["plantMaxTemp"], $row["plantSoilMoisture"]);
				$plants[$plants->key()] = $plant;
				$plants->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($plants);
	}

	/**
	 * Get all Plant objects.
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray of Plant objects found or null if none found.
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type.
	 **/
	public static function getAllPlants(\PDO $pdo){
		//create query template
		$query = "SELECT plantId, plantName, plantVariety, plantDescription, plantType, plantSpread, plantDaysToHarvest, plantHeight, plantMinTemp, plantMaxTemp, plantSoilMoisture FROM plant";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of plant entries
		$plants = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row=$statement->fetch())!== false){
			try {
				$plant = new Plant($row["plantId"], $row["plantName"], $row["plantVariety"], $row["plantDescription"], $row["plantType"], $row["plantSpread"], $row["plantDaysToHarvest"], $row["plantHeight"], $row["plantMinTemp"], $row["plantMaxTemp"], $row["plantSoilMoisture"]);
				$plants[$plants->key()] = $plant;
				$plants->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($plants);
	}

}