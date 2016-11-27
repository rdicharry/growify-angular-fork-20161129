<?php
namespace Edu\Cnm\Growify;

use Exception;
use TypeError;
use DateTime;

require_once("autoload.php");

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
	 * start day for this PlantArea
	 * @var int $plantAreaStartDay
	 **/
	private $plantAreaStartDay;

	/**
	 * end day for this PlantArea
	 * @var int $plantAreaEndDay
	 **/
	private $plantAreaEndDay;


	/**
	 * start month for this PlantArea
	 * @var int $plantAreaStartMonth
	 **/
	private $plantAreaStartMonth;


	/**
	 * end month for this PlantArea
	 * @var int $plantAreaEndMonth
	 *
	 **/
	private $plantAreaEndMonth;

	/**
	 * area number for this PlantArea
	 * @var string $plantAreaNumber
	 */
	private $plantAreaNumber;
	/**
	 * change: a variable used to represent the maximum number a SMALLINT can be. A SMALLINT is what will hold the plantId in the database
 	 * Maximum unsigned smallint value that the plantId field cannot exceed
	 * @var int $MAX_PLANTID
 	*/
	private   $MAX_PLANTID = 65535;

	/**
	 * change: a variable used to represent the maximum number a SMALLINT can be. A SMALLINT is what will hold the plantAreaId in the database
	 * Maximum unsigned smallint value that the plantId field cannot exceed
	 * @var int $MAX_PLANTAREAID
	 */
	private  $MAX_PLANTAREAID = 65535;


	/**
	 * constructor for this PlantArea
	 *
	 * plantAreaId is not included in the parameters because it will be set once inserted into a table.
	 *
	 * @param int $newPlantAreaId
	 * @param int $newPlantAreaPlantId
	 * @param int $newPlantAreaStartDay
	 * @param int $newPlantAreaEndDay
	 * @param int $newPlantAreaStartMonth
	 * @param int $newPlantAreaEndMonth
	 * @param string $newPlantAreaNumber
	 * @throws Exception if some other exception occurs
	 * @throws TypeError if data types violate type hints
	 */
	public function __construct($newPlantAreaId, $newPlantAreaPlantId, $newPlantAreaStartDay, $newPlantAreaEndDay, $newPlantAreaStartMonth, $newPlantAreaEndMonth,string $newPlantAreaNumber) {
		try {
			$this->setPlantAreaId ($newPlantAreaId);
			$this->setPlantAreaPlantId($newPlantAreaPlantId);
			$this->setPlantAreaStartMonthAndDay($newPlantAreaStartMonth, $newPlantAreaStartDay);
			$this->setPlantAreaEndMonthAndDay($newPlantAreaEndMonth, $newPlantAreaEndDay);
			$this->setPlantAreaNumber($newPlantAreaNumber);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the execption to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
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
	 * @throws \OutOfBoundsException if $newPlantAreaId is not positive or greater than the largest unsigned SMALLINT value
	 * @throws TypeError if $newPlantAreaId is not an integer
	 **/
	public function setPlantAreaId($newPlantAreaId) {
		// base case: if the plant area id is null, this new plant area without mySQL assigned id (yet)
		if($newPlantAreaId === null) {
			$this->plantAreaId = null;
			return;
		}
		if($newPlantAreaId <= 0 || $newPlantAreaId > $this->MAX_PLANTAREAID) { //change: verify the plant area id is positive and less than or equal to the largest unsigned SMALLINT value
			throw(new \OutOfBoundsException("This Plant Area Id is not a valid value (0-65535)"));
		}
		// convert and store the plant area id
		$this->plantAreaId = $newPlantAreaId;
	}

	/**
	 * accessor method for plant area plant id
	 *
	 * @return int value of plant area plant id
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
	public function setPlantAreaPlantId(int $newPlantAreaPlantId) {
		// verify the plant area plant id is positive or above the Max Plant Id value changed
		if($newPlantAreaPlantId <= 0 || $newPlantAreaPlantId > $this->MAX_PLANTID) {
			throw(new \RangeException("plant area plant id is out of range"));
		}
		// convert and store the plant area plant id
		$this->plantAreaPlantId = $newPlantAreaPlantId;
	}

	/**
	 * accessor method for plant area start day
	 *
	 * $return int value of plant area start day
	 **/
	public function getPlantAreaStartDay() {
		return ($this->plantAreaStartDay);
	}



	/**
	 * accessor method for plant area end day
	 *
	 * $return int value of plant area end day
	 **/
	public function getPlantAreaEndDay() {
		return ($this->plantAreaEndDay);
	}



	/**
	 * verify a set of integers represents a valid date. e.g.
	 * $month = 13 and $day = 42 is NOT a valid date!
	 * Includes a check for leap year (assuming the current year).
	 * @param int $month
	 * @param int $day
	 * @return bool
	 * @throws \RangeException if the given month and day cannot be found in the current calendar year.
	 */
	public static function validateDate(int $month, int $day){

		// grab current year
		$currentDateTime = new DateTime();
		$date = getDate($currentDateTime->getTimestamp());
		$year = $date["year"];

		if(!checkdate($month, $day, $year)){
			return false;
		}

		return true;
	}


	/**
	 * accessor method for plant area start month
	 *
	 * $return int value of plant area start date
	 **/
	public function getPlantAreaStartMonth() {
		return ($this->plantAreaStartMonth);
	}

	/**
	 * mutator method for plant area start month
	 *
	 * @param int $newPlantAreaStartMonth plant area start month
	 * @param int $newPlantAreaStartDay plant area start day
	 * @throws \TypeError if $newPlantAreaStartMonth is not an integer
	 * @throws \OutOfBoundsException if $newPlantAreaEndMonth is not a valid day of the month (Less than 1 or greater than 31)
	 **/
	public function setPlantAreaStartMonthandDay(int $newPlantAreaStartMonth, int $newPlantAreaStartDay) {
		//check if $newPlantAreaStartMonth is an int, if not throw TypeError
		if($newPlantAreaStartMonth < 1 || $newPlantAreaStartMonth > 12){
			throw (new \RangeException("This plantAreaStartMonth is not a valid day of the month"));
		}
		if($newPlantAreaStartDay < 1 || $newPlantAreaStartDay > 31){
			throw (new \RangeException("This plantAreaStartMonth is not a valid day of the month"));
		}
		if(!self::validateDate($newPlantAreaStartMonth, $newPlantAreaStartDay)) {
			throw(new \RangeException("not a valid plant area start date"));
		}

		$this->plantAreaStartMonth = $newPlantAreaStartMonth;
		$this->plantAreaStartDay = $newPlantAreaStartDay;
	}

	/**
	 * accessor method for plant area end month
	 *
	 * $return int value of plant area end month
	 **/
	public function getPlantAreaEndMonth() {
		return ($this->plantAreaEndMonth);
	}

	/**
	 * mutator method for plant area end month
	 *
	 * @param int $newPlantAreaEndMonth plant area end month
	 * @param int $newPlantAreaEndDay plant area end day
	 * @throws \TypeError if $newPlantAreaEndMonth is not an integer
	 * @throws \OutOfBoundsException if $newPlantAreaEndMonth is not a valid day of the month (Less than 1 or greater than 31)
	 **/
	public function setPlantAreaEndMonthAndDay(int $newPlantAreaEndMonth, int $newPlantAreaEndDay) {
		//check if $newPlantAreaEndMonth is an int, if not throw TypeError
		if($newPlantAreaEndMonth < 1 || $newPlantAreaEndMonth > 12){
			throw (new \RangeException("This plantAreaStartMonth is not a valid day of the month"));
		}
		if($newPlantAreaEndDay < 1 || $newPlantAreaEndDay > 31){
			throw (new \RangeException("This plantAreaStartMonth is not a valid day of the month"));
		}

		if(!self::validateDate($newPlantAreaEndMonth, $newPlantAreaEndDay)){
			throw(new \RangeException("not a valid date"));
		}

		$this->plantAreaEndMonth = $newPlantAreaEndMonth;
		$this->plantAreaEndDay = $newPlantAreaEndDay;
	}


	/**
	 * accessor method for plant area area number
	 *
	 * @return int value of plant area profile id
	 **/
	public function getPlantAreaNumber() {
		return ($this->plantAreaNumber);
	}

	/**
	 * @mutator method for plant area area number
	 *
	 * @param string $newPlantAreaNumber the new area that will be passed into this PlantArea's plantAreaNumber field
	 * @throws TypeError if $newPlantAreaNumber is not a string
	 *
	 * @throws \InvalidArgumentException if $newPlantAreaNumber does not contian an integer between 4 an nine, or
	 * does not end with a character that is either 'a' or 'b'
	 */
	public function setPlantAreaNumber(string $newPlantAreaNumber) {
		//change: makes sure the $newPlantAreaNumber is a string
		if(self::isValidPlantAreaNumber($newPlantAreaNumber)) {
			// convert and store the plant area area number
			$this->plantAreaNumber = $newPlantAreaNumber;
		} else {
			throw( new \InvalidArgumentException("This is not a valid New Mexico Plant Area Growing Zone"));
		}
	}

	/**
	 * Check whether a string represents a valid New Mexico plant area number.
	 * These are a combination of an integer 4 - 9 and a letter a or b
	 * @param string $newPlantAreaNumber
	 * @return bool true if the plant area number is valid, false otherwise.
	 */
	public static function isValidPlantAreaNumber(string $newPlantAreaNumber){
		//change: makes sure the $newPlantAreaNumber is a string

			if(strlen($newPlantAreaNumber) !== 2) { //change: makes sure the string contains two characters
				return false;
			} elseif(intval(substr($newPlantAreaNumber, 0, 1)) > 9 || intval(substr($newPlantAreaNumber, 0, 1)) < 4) {
				//change: Validates the $newPlantAreaNumber, making sure that it is an integer and between 4-9 in value (The 4 NM growing zones)
				return false;
			} elseif((substr($newPlantAreaNumber, 1) !== 'a' && substr($newPlantAreaNumber, 1) !== 'b')) {
				return false; //change: makes sure the last character of the Plant Area Area Number is either a or b (a valid new mexico growing zone consists of a number from 4-8 followed by a character that is either a or b
			}

		return true;

	}

	/**
	 * Updates the plantArea table with this PlantArea instance's state variables
	 *
	 * @param \PDO $pdo the php data object used to update the plantArea table
	 * @throws \PDOException if an error regarding the php data object occured
	 */
	public function update(\PDO $pdo) {
		//Checks if the plantAreaId exists
		if($this->plantAreaId === null) {
			throw(new \PDOException("This plant area cannot be updated because it doesnt exist."));
		}
		try {
			$query = "UPDATE plantArea SET plantAreaPlantId = :plantAreaPlantId, plantAreaStartDay = :plantAreaStartDay, plantAreaEndDay = :plantAreaEndDay, plantAreaStartMonth = :plantAreaStartMonth, plantAreaEndMonth = :plantAreaEndMonth, plantAreaNumber = :plantAreaNumber WHERE plantAreaId = :plantAreaId";
			$statement = $pdo->prepare($query);
			$parameters = ["plantAreaId" => $this->plantAreaId, "plantAreaPlantId" => $this->plantAreaPlantId, "plantAreaStartDay" => $this->plantAreaStartDay, "plantAreaEndDay" => $this->plantAreaEndDay, "plantAreaStartMonth" => $this->plantAreaStartMonth, "plantAreaEndMonth" => $this->plantAreaEndMonth,"plantAreaNumber" => $this->plantAreaNumber];
			$statement->execute($parameters);
		}catch(\PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * Inserts a row into the plantArea table that represents this plantArea's instance
	 *
	 * @param \PDO $pdo the php data object used to delete a row from the the plantArea table
	 * @throws \PDOException if an error regarding the php data object occured
	 */
	public function insert(\PDO $pdo){
		if($this->plantAreaId !== null){
			throw(new \PDOException("This Plant Area cannot be inserted into plantArea table because it already exists in the plant area table"));
		}

			$query = "INSERT INTO plantArea(plantAreaPlantId, plantAreaStartDay, plantAreaEndDay, plantAreaStartMonth, plantAreaEndMonth, plantAreaNumber) VALUES(:plantAreaPlantId, :plantAreaStartDay, :plantAreaEndDay, :plantAreaStartMonth, :plantAreaEndMonth, :plantAreaNumber)";
			$statement = $pdo->prepare($query);

			$parameters = ["plantAreaPlantId" => $this->plantAreaPlantId, "plantAreaStartDay" => $this->plantAreaStartDay, "plantAreaEndDay" => $this->plantAreaEndDay, "plantAreaStartMonth" => $this->plantAreaStartMonth, "plantAreaEndMonth" => $this->plantAreaEndMonth,"plantAreaNumber" => $this->plantAreaNumber];
			$statement->execute($parameters);
			//set plantAreaId to integer value given by mySql
			$this->plantAreaId = (intval($pdo->lastInsertId()));

	}

	/**
	 * Deletes the row representing this Plant Area instance from the Plant Area table
	 *
	 * @param \PDO $pdo the php data object used to delete a row from the the Plant Area table
	 * @throws \PDOException if an error regarding the php data object occured
	 */
	public function delete(\PDO $pdo) {
		if(is_null($this->plantAreaId)){
			throw (new \PDOException("Cannot delete plant area because it does not exist in the plant area table"));
		}
		try {
			$query = "DELETE FROM plantArea WHERE plantAreaId = :plantAreaId";
			$statement = $pdo->prepare($query);
			$parameters = ["plantAreaId" => $this->plantAreaId];
			$statement->execute($parameters);
		}catch(\PDOException $pdoException){
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
		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDay, plantAreaEndDay, plantAreaStartMonth, plantAreaEndMonth, plantAreaNumber FROM plantArea";
		$statement = $pdo->prepare($query);
		$statement->execute();
		$plantAreas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$plantArea = new plantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDay"], $row["plantAreaEndDay"], $row["plantAreaStartMonth"], $row["plantAreaEndMonth"], $row["plantAreaNumber"]);
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
	public static function getPlantAreaByPlantAreaId(\PDO $pdo, $plantAreaId){
		if(!is_int($plantAreaId )|| ($plantAreaId > 65535)){
			throw(new \TypeError("plantAreaId is not valid" . $plantAreaId));
		}

		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDay, plantAreaEndDay, plantAreaStartMonth, plantAreaEndMonth ,plantAreaNumber FROM plantArea WHERE plantAreaId = :plantAreaId";
		$statement = $pdo->prepare($query);
		$parameters = ["plantAreaId" => $plantAreaId];
		$statement->execute($parameters);

		try {
			$plantArea = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$plantArea = new PlantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDay"], $row["plantAreaEndDay"], $row["plantAreaStartMonth"], $row["plantAreaEndMonth"], $row["plantAreaNumber"]);
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
	public static function getAllPlantAreasByPlantAreaPlantId(\PDO $pdo,int $plantAreaPlantId) {

		if($plantAreaPlantId < 0){
			throw(new \PDOException("plant Id is not valid"));
		}

		$query = "SELECT plantAreaId, plantAreaPlantId, plantAreaStartDay, plantAreaEndDay, plantAreaStartMonth, plantAreaEndDay, plantAreaNumber FROM plantArea WHERE plantAreaPlantId = :plantAreaPlantId";
		$statement = $pdo->prepare($query);
		$parameters = ["plantAreaPlantId"=> $plantAreaPlantId];
		$statement->execute($parameters);
		$plantAreas = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$plantArea = new plantArea($row["plantAreaId"], $row["plantAreaPlantId"], $row["plantAreaStartDay"], $row["plantAreaEndDay"], $row["plantAreaStartMonth"],$row["plantAreaEndMonth"], $row["plantAreaNumber"]);
				$plantAreas[$plantAreas->key()] = $plantArea;
				$plantAreas->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($plantAreas);
	}

	/**
	 * Find a plant area with a given plantId and plant area number.
	 * This is essential to provide the functionality of being able to find the
	 * planting dates for a given plant knowing which area it will be grown in.
	 * @param \PDO $pdo a PDO connection object
	 * @param int $plantId a valid plant Id.
	 * @param string $areaNumber a valid New Mexico plant area number.
	 * @return PlantArea|null return the plant Area object found, or null if nothing found.
	 * @throws \PDOException if the inputs are invalid or a mySQL connection-related exception occurs.
	 */
	public static function getPlantAreaByPlantIdAndArea(\PDO $pdo,int $plantId, string $areaNumber){

		if(!self::isValidPlantAreaNumber($areaNumber)){
			throw(new \PDOException("Area Number is invalid."));
		}

		if($plantId < 0){
			throw(new \PDOException("plant Id is not valid."));
		}

		$query = "SELECT plantAreaId, plantAreaStartDay, plantAreaEndDay, plantAreaStartMonth, plantAreaEndDay FROM plantArea WHERE plantAreaPlantId = :plantId & plantAreaNumber = :areaNumber";
		$statement = $pdo->prepare($query);
		$parameters = ["plantId" => $plantId, "areaNumber"=>$areaNumber];
		$statement->execute($parameters);

		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$plantArea = null;
		try{
		$row = $statement->fetch();

			if($row !== false) {
				$plantArea = new PlantArea($row["plantAreaId"], $plantId, $row["plantAreaStartDay"], $row["plantAreaEndDay"], $row["plantAreaStartMonth"], $row["plantAreaEndMonth"], $areaNumber);
			}
		} catch(\Exception $exception) {
		// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($plantArea);
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
