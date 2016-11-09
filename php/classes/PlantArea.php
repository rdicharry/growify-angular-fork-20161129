<?php

/**
 * Creating class for PlantArea
 *
 * This is the class for the PlantArea for the Growify capstone.
 *
 * @author Ana Vela <avela7@cnm.edu>
 * @version 1.0.0
 *
 *
 */
class PlantArea implements \JsonSerializable {

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
	 * @var string $plantAreaStartDate
	 **/
	private $plantAreaStartDate;

	/**
	 * end date for this PlantArea
	 * @var string $plantAreaEndDate
	 *
	 **/
	private $plantAreaEndDate;

	/**
	 * area number for this PlantArea
	 * @var string $plantAreaAreaNum
	 */
	private $plantAreaAreaNum;
	/**
	 * change: a variable used to represent the maximum number a SMALLINT can be. A SMALLINT is what will hold the plantId in the database
 	 * Maximum unsigned smallint value that the plantId field cannot exceed
	 * @var int $MAX_PLANTID
 	*/
	private $MAX_PLANTID = 65535;

	/**
	 * change: a variable used to represent the maximum number a SMALLINT can be. A SMALLINT is what will hold the plantAreaId in the database
	 * Maximum unsigned smallint value that the plantId field cannot exceed
	 * @var int $MAX_PLANTAREAID
	 */
	private $MAX_PLANTAREAID = 65535;


	/**
	 * constructor for this PlantArea
	 *
	 * plantAreaId is not included in the parameters because it will be set once inserted into a table.
	 *
	 * @param int $newPlantAreaId
	 * @param string $newPlantAreaStartDate
	 * @param string $newPlantAreaEndDate
	 * @param string $newPlantAreaAreaNum
	 * @throws Exception if some other exception occurs
	 * @throws TypeError if data types violate type hints
	 * @internal param int|null $plantAreaId id for this PlantId
	 * @internal param null|string $plantAreaPlantId plant id for this plant area
	 * @internal param string $plantAreaStartDate start date for this PlantArea
	 * @internal param string $plantAreaEndDate end date for this PlantArea
	 * @internal param int|null $plantAreaAreaNum the area number of this PlantArea
	 */
	public function __construct($newPlantAreaPlantId, $newPlantAreaStartDate, $newPlantAreaEndDate, $newPlantAreaAreaNum) {
		try {
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
	 * @throws OutOfBoundsException if $newPlantAreaId is not positive or greater than the largest unsigned SMALLINT value
	 * @throws TypeError if $newPlantAreaid is not an integer
	 **/
	public function setPlantAreaId($newPlantAreaId) {
		if(is_int($newPlantAreaId)){ //change: makes sure newPlantAreaId is an integer
			throw (new TypeError("This Plant Area Id Number is not an integer"));
		}elseif($newPlantAreaId <= 0 || $newPlantAreaId <= $this->MAX_PLANTAREAID) { //change: verify the plant area id is positive and less than or equal to the largest unsigned SMALLINT value
			throw(new \OutOfBoundsException("This Plant Area Id is not a valid value (0-65535)"));
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
		// verify the plant area plant id is positive or above the Max Plant Id value changed
		if(!is_int($newPlantAreaPlantId)){
			throw(new \TypeError('New plantAreaPlantId is not an integer'));
		}else if($newPlantAreaPlantId <= 0 || $newPlantAreaPlantId > $this->MAX_PLANTID) {
			throw(new \RangeException("plant area plant id is out of range"));
		}
		// convert and store the plant area plant id
		$this->plantAreaPlantId = $newPlantAreaPlantId;
	}

	/**
	 * accessor method for plant area start date
	 *
	 * $return string value of plant area start date
	 **/
	public function getPlantAreaStartDate() {
		return ($this->plantAreaStartDate);
	}

	/**
	 * mutator method for plant area start date
	 *
	 * @param string $newPlantAreaStartDate plant area start date as a DateTime object or string
	 * @throw \InvalidArgumentException if $newPlantAreaStartDate is a date that does not exist
	 * @throws \TypeError if $newPlantAreaStartDate is not a string
	 * @throws \OutOfBoundsException if $newPlantAreaStartDate is contains more than 5 characters
	 **/
	public function setPlantAreaStartDate($newPlantAreaStartDate) {
		//check if $newPlantAreaStartDate is a string, if not throw TypeError
		if(!is_string($newPlantAreaStartDate)){
			throw(new \TypeError("Plant Area Start Date is not a string"));
		}elseif(strlen($newPlantAreaStartDate) > 5){  //change: If plant Area Start Date is longer than 5 characters it will throw an Out of Bounds Error
			throw (new OutOfBoundsException("This plantareastartdate is greater than 5 characters long"));
		} elseif((int)(substr($newPlantAreaStartDate,0,1)) > 31 || (int)(substr($newPlantAreaStartDate,3)) > 12 || (int)(substr($newPlantAreaStartDate,0,1) < 1 || (int)(substr($newPlantAreaStartDate,3) < 1))){ //change: this elseif statement checks the first two and last two numbers of the plantareastartdate and sees if they are valid (makes sure that the day isn't less than 1 or greater than 31, and makes sure the last two characters aren't greater than 12 or less than 1, I can do this because I am briefly turning these strings into numbers using an integer cast by saying (int)([string numbers]). You can do this as long as the string contains only numbers.
			throw (new InvalidArgumentException('Plant Area Start Date is not a valid Date: "Day/Month"'));
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
	 * @param string $newPlantAreaEndDate plant area end date as a DateTime object or string
	 * @throws TypeError if $newPlantAreaEndDate contains more than 5 characters
	 * @throws InvalidArgumentException if $newPlantAreaEndDate is a date that does not exist
	 * @throws OutOfBoundsException if $newPlantAreaEndDate is greater than 5 characters long
	 **/
	public function setPlantAreaEndDate($newPlantAreaEndDate) {
		//check if $newPlantAreaEndDate is a string, if not throw TypeError
		if(!is_string($newPlantAreaEndDate)){
			throw(new \TypeError("Plant Area End Date is not a string"));
		}elseif(strlen($newPlantAreaEndDate) > 5){  //change: If plant Area End Date is longer than 5 characters it will throw an Out of Bounds Error
			throw (new OutOfBoundsException("Plant Area End Date is greater than 5 characters long"));
		} elseif((int)(substr($newPlantAreaEndDate,0,1)) > 31 || (int)(substr($newPlantAreaEndDate,3)) > 12 || (int)(substr($newPlantAreaEndDate,0,1) < 1 || (int)(substr($newPlantAreaEndDate,3) < 1))){ //change: this elseif statement checks the first two and last two numbers of the $newPlantAreaEndDate and sees if they are valid (makes sure that the day isn't less than 1 or greater than 31, and makes sure the last two characters aren't greater than 12 or less than 1, I can do this because I am briefly turning these strings into numbers using an integer cast by saying (int)([string numbers]). You can do this as long as the string contains only numbers.
			throw (new InvalidArgumentException('Plant Area End Date is not a valid Date: "Day/Month"'));
		}

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
	 * @param string $newPlantAreaAreaNum the new area that will be passed into this PlantArea's plantAreaAreaNum field
	 * @throws TypeError if $newPlantAreaAreaNum is not a string
	 * @throws OutOfBoundsException if $newPlantAreaAreaNum is not 2 characters long
	 * @throws InvalidArgumentException if $newPlantAreaAreaNum does not begin with a number ranging from 4-8
	 * @throws InvalidArgumentException if $newPlantAreaAreaNum does not end with a character that is either 'a' or 'b'
	 */
	public function setPlantAreaAreaNum($newPlantAreaAreaNum) {
		//change: makes sure the $newPlantAreaAreaNum is a string
		if(!is_string($newPlantAreaAreaNum)){
			throw (new \TypeError("This Plant Area Growing Zone is not a string"));
		} elseif(strlen($newPlantAreaAreaNum)!= 2){ //change: makes sure the string contains two characters
			throw (new \OutOfBoundsException("This is not a valid New Mexico Plant Area Growing Zone"));
		} elseif((int)(substr($newPlantAreaAreaNum,0,0)) < 8 || (int)(substr($newPlantAreaAreaNum,0,0)) > 4){
			//change: Validates the $newPlantAreaAreaNum, making sure that it is an integer and between 4-8 in value (The 4 NM growing zones)
			throw (new InvalidArgumentException("This Plant Area Area Value is not a valid New Mexco growing zone"));
		} elseif(substr($newPlantAreaAreaNum,1) != 'a' && substr($newPlantAreaAreaNum,1) != 'b'){
			throw (new InvalidArgumentException("This Plant Area Area Value is not a valid New Mexco Growing Zone")); //change: makes sure the last character of the Plant Area Area Number is either a or b (a valid new mexico growing zone consists of a number from 4-8 followed by a character that is either a or b
		}
		// convert and store the plant area area number
		$this->plantAreaAreaNum = $newPlantAreaAreaNum;
	}

	/**
	 * Updates the plantArea table with this PlantArea instance's state variables
	 *
	 * @param PDO $pdo the php data object used to update the plantArea table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function update(\PDO $pdo) {
		//Checks if the plantAreaId exists
		if($this->plantAreaId === null) {
			throw(new \PDOException("This plant area cannot be updated because it doesnt exist."));
		}
		try {
			$query = "UPDATE plantArea SET plantAreaPlantId = :plantAreaPlantId, plantAreaStartDate = :plantAreaStartDate, plantAreaEndDate = :plantAreaEndDate, plantAreaAreaNum = :plantAreaAreaNum WHERE plantAreaId = :plantAreaId";
			$statement = $pdo->prepare($query);
			$parameters = ["plantAreaPlantId" => $this->plantAreaPlantId, "plantAreaStartDate" => $this->plantAreaStartDate, "plantAreaEndDate" => $this->plantAreaEndDate, "plantAreaAreaNum" => $this->plantAreaAreaNum];
			$statement->execute($parameters);
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * Inserts a row into the plantArea table that represents this plantArea's instance
	 *
	 * @param PDO $pdo the php data object used to delete a row from the the plantArea table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function insert(\PDO $pdo){
		if(is_null($this->plantAreaId)){
			throw(new PDOException("This Plant Area cannot be inserted into plantArea table because it already exists in the plant area table"));
		}

		try {
			$query = "INSERT INTO plantArea(plantAreaPlantId, plantAreaStartDate, plantAreaEndDate, plantAreaAreaNum) VALUES(:plantAreaPlantId, :plantAreaStartDate, plantAreaEndDate, :plantAreaAreaNum)";
			$statement = $pdo->prepare($query);

			$parameters = ["plantAreaPlantId" => $this->plantAreaPlantId, "plantAreaStartDate" => $this->plantAreaStartDate, "plantAreaEndDate"=> $this->plantAreaEndDate, "plantAreaAreaNum" => $this->plantAreaAreaNum];
			$statement->execute($parameters);
			//set plantAreaId to integer value given by mySql
			$this->setPlantAreaId(intval($pdo->lastInsertId()));
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * Deletes the row representing this Plant Area instance from the Plant Area table
	 *
	 * @param PDO $pdo the php data object used to delete a row from the the Plant Area table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function delete(\PDO $pdo) {
		if(is_null($this->plantAreaId)){
			throw (new PDOException("Cannot delete plant area because it does not exist in the plant area table"));
		}
		try {
			$query = "DELETE FROM plantArea WHERE plantAreaId = :plantAreaId";
			$statement = $pdo->prepare($query);
			$parameters = ["plantAreaId" => $this->plantAreaId];
			$statement->execute($parameters);
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * gets all plant areas
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of plant areas found, returns null if empty
	 * @throws \PDOException if an error regarding the php data object occurs
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllPlantAreas(\PDO $pdo) {
		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDate, plantAreaEndDate, plantAreaAreaNum FROM plantArea";
		$statement = $pdo->prepare($query);
		$statement->execute();
		$plantAreas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$plantArea = new plantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDate"], $row["plantAreaEndDate"], $row["plantAreaAreaNum"]);
				$plantAreas[$plantAreas->key()] = $plantArea;
				$plantAreas->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($plantAreas);
	}

	/**
	 * gets a Plant Area instance from a plantAreaId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param \int The plantAreaId to search for
	 * @return PlantArea|null PlantArea found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public function getPlantAreaByPlantId(\PDO $pdo, $plantAreaId){
		if(!is_int($plantAreaId || $plantAreaId < $this->MAX_PLANTAREAID)) {
			throw(new \TypeError("plantAreaId is not valid"));
		}

		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDate, plantAreaEndDate, plantAreaAreaNum FROM plantArea WHERE plantAreaId = :plantAreaId";
		$statement = $pdo->prepare($query);
		$parameters = ["plantAreaId" => $plantAreaId];
		$statement->execute($parameters);

		try {
			$plantArea = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$plantArea = new PlantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDate"], $row["plantAreaEndDate"], $row["plantAreaAreaNum"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($plantArea);
	}

	/**
	 * gets all plant areas by a certain plant area plant Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $plantAreaPlantId an id referencing a plantAreaPlantId to search for
	 * @return \SplFixedArray SplFixedArray of plant areas found, returns null if empty
	 * @throws \PDOException if an error regarding the php data object occurs
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllPlantAreasbyPlantAreaPlantId(\PDO $pdo, $plantAreaPlantId) {
		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDate, plantAreaEndDate, plantAreaAreaNum FROM plantArea WHERE plantAreaPlantId = :plantAreaPlantId";
		$statement = $pdo->prepare($query);
		$parameters = ["plantAreaPlantId"=> $plantAreaPlantId];
		$statement->execute($parameters);
		$plantAreas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$plantArea = new plantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDate"], $row["plantAreaEndDate"], $row["plantAreaAreaNum"]);
				$plantAreas[$plantAreas->key()] = $plantArea;
				$plantAreas->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($plantAreas);
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
