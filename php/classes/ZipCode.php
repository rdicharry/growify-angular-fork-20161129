<?php
/**
 * Created by PhpStorm.
 * User: Growify
 * Date: 11/2/2016
 * Time: 3:58 PM
 */

class ZipCode{
	/**
	 * @var String the Zip Code corresponding to a USDA Grow Zone (zipCodeArea)
	 */
	private $zipCodeCode;

	/**
	 * @var string the zipCodeArea USDA GrowZone that corresponds to a United States Postal ZipCode
	 */
	private $zipCodeArea;
	/**
	 * ZipCode constructor.
	 *
	 * @param $zipCodeCode string a five character string that is used as the primary key in the zipCode table, represents a new mexico zipcode.
	 * @param $zipCodeArea string a 2 character string that is used as the zipCodeArea in the zipCode Table, represents a USDA grow zone in New Mexico
	 * @throws TypeError if a type other than string was passed for either $zipCodeCode or $zipCodeArea.
	 * @throws OutOfBoundsException if a string greater or less than 5 characters was passed through $zipCodeCode or greater than less than 2 was passed through $zipCodeArea
	 * @throws InvalidArgumentException if a string passed through zipCodeCode or zipCodeArea has failed to validate (not legitimate grow area or zip code)
	 * @throws Exception if an otherwise unspecified error was thrown.
	 */
	public function __construct($zipCodeCode, $zipCodeArea) {
		try {
			$this->setZipCodeCode($zipCodeCode);
			$this->setZipCodeArea($zipCodeArea);
		}catch(TypeError $typeError){
			throw(new \TypeError($typeError->getMessage(),0,$typeError));
		}catch(OutOfBoundsException $outOfBoundsException){
			throw(new \TypeError($outOfBoundsException->getMessage(),0,$outOfBoundsException));
		}catch(InvalidArgumentException $invalidArgumentException){
			throw(new \InvalidArgumentException($invalidArgumentException->getMessage(),0,$invalidArgumentException));
		}catch(Exception $exception){
			throw(new \Exception($exception->getMessage(),0,$exception));
		}
	}

	/**
	 * Sets the value of this ZipCode object's zipCodeCode to zipCodeCode
	 * @param $zipCodeCode string the value of zipCodeArea
	 *
	 * @throws TypeError if the parameter $zipCodeCode is not a string
	 * @throws OutOfBoundsException if the parameter $zipCodeCode is not 5 characters long (the length of a New Mexico Zipcode)
	 * @throws InvalidArgumentException if the parameter $zipCodeCode does not begin with 87 or 88 (The only beginning characters of a New Mexico ZipCode)
	 */
	public function setZipCodeCode($zipCodeCode) {
		//Validates $zipCodeCode to make sure it is a string that is 5 characters long beginning with either 87 or 88
		if(!is_string($zipCodeCode)){
			throw (new \TypeError('The $zipCodeCode Entered is not a String'));
		}elseif(strlen($zipCodeCode) != 5){
			throw (new \OutOfBoundsException('The $zipCodeCode Entered is not 5 characters long and is therefore an invalid New Mexico Zip Code'));
		}
		elseif(substr($zipCodeCode,0,1) != '87' &&  substr($zipCodeCode, 0,1) !='88'){
			throw (new \InvalidArgumentException('The $zipCodeCode entered is not a valid New Mexico Zip Code'));
		}

		$this->$zipCodeCode = $zipCodeCode;
	}

	/**
	 * Sets the value of this ZipCode object's zipCodeArea to zipCodeArea
	 *
	 * @param string $zipCodeArea the zipCodeArea that will be set
	 * @throws TypeError if the parameter $zipCodeArea is not a string
	 * @throws OutOfBoundsException if the parameter $zipCodeArea is not 2 characters long
	 * @throws InvalidArgumentException if the parameter $zipCodeArea does not start with the characters 4,5,6,7, or 8 (start of valid growing zone)
	 * @throws InvalidArgumentException if the parameter $zipCodeArea does not end with the characters a or b.
	 *
	 */
	public function setZipCodeArea($zipCodeArea) {
		//Validates the Zip code area, making sure that it is an intenger and between 1-3 in value (The three NM growing zones)
		if(!is_string($zipCodeArea)){
			throw (new \TypeError("This zip code area is not an string"));
		} elseif(strlen($zipCodeArea)!= 2){
			throw (new \OutOfBoundsException("This is not a valid New Mexico growing Zone"));
		} elseif(substr($zipCodeArea,0,0)!= '4' && substr($zipCodeArea,0,0)!= '5' && substr($zipCodeArea,0,0)!= '6' && substr($zipCodeArea,0,0)!= '7' && substr($zipCodeArea,0,0)!= '8'){
			throw (new InvalidArgumentException("This zip code area is not a valid New Mexco growing zone"));
		} elseif(substr($zipCodeArea,1) != 'a ' && substr($zipCodeArea,1) != 'b '){
			throw (new InvalidArgumentException("This zip code area is not a valid New Mexco growing zone"));
		}
		//Set this object's value of zipCodeArea to the specified ZipCodeArea in the Parameter
		$this->$zipCodeArea;
	}

	/**
	 * Returns the value of a $zipCodeArea associated with this Zipcode instance
	 *
	 * @return int $zipCodeArea The area associated with a Zipcode Object
	 */
	public function getZipCodeArea() {
		return $this->zipCodeArea;
	}

	 /**
	  * Returns the value of this ZipCode instance's zipCodeCode
	  * @returns string zipCodeCode of this ZipCode instance, represents a New Mexico Zip Code
	 */
	public function getZipCodeCode() {
		return $this->zipCodeCode;
	}

	/**
	 * Inserts a row into the zipCode table that represents this ZipCode instance
	 *
	 * @param PDO $pdo the php data object used to delete a row from the the zipCode table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function insert(\PDO $pdo){
		try {
			$query = "INSERT INTO zipCode(zipCodeCode, zipCodeArea) VALUES(:zipCodeCode, :zipCodeArea)";
			$statement = $pdo->prepare($query);

			$parameters = ["zipCodeCode" => $this->zipCodeCode, "zipCodeArea" => $this->zipCodeArea];
			$statement->execute($parameters);
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}

	}
	/**
	 * Deletes the row representing this ZipCode instance from the zipCode table
	 *
	 * @param PDO $pdo the php data object used to delete a row from the the zipCode table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function delete(\PDO $pdo) {
		//checks if zipCodeCode exists.
		if($this->zipCodeCode === null) {
			throw(new \PDOException("This zipcode cannot be deleted because it doesn't exist"));
		}
		try {
			$query = "DELETE FROM zipCode WHERE zipCodeCode = :zipCodeCode";
			$statement = $pdo->prepare($query);
			$parameters = ["zipCodeCode" => $this->zipCodeCode];
			$statement->execute($parameters);
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * Updates the zipCode table with this zipCode instance's state variables
	 *
	 * @param PDO $pdo the php data object used to update the zipCode table
	 * @throws PDOException if an error regarding the php data object occured
	 */
	public function update(\PDO $pdo) {
		//Checks if the zipCodeCode exists
		if($this->zipCodeCode === null) {
			throw(new \PDOException("This zipcode cannot be updated because it doesnt exist."));
		}
		try {
			$query = "UPDATE zipCode SET zipCodeCode = :zipCodeCode, zipCodeArea = :zipCodeArea";
			$statement = $pdo->prepare($query);
			$parameters = ["zipCodeCode" => $this->zipCodeCode, "zipCodeArea" => $this->zipCodeArea];
			$statement->execute($parameters);
		}catch(PDOException $pdoException){
			throw(new \PDOException($pdoException->getMessage(),0,$pdoException));
		}
	}

	/**
	 * gets a ZipCode from a zipCodeCode
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $zipCodeCode The zipCodeCode to search for
	 * @return ZipCode|null ZipCode found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getZipCodeByZipCodeCode(\PDO $pdo, $zipCodeCode) {
		if(!is_string($zipCodeCode)) {
			throw(new \TypeError("Zip Code is not a String"));
		}

		$query = "SELECT zipCodeCode,zipCodeArea FROM zipCode WHERE zipCodeCode = :zipCodeCode";
		$statement = $pdo->prepare($query);
		$parameters = ["zipCodeCode" => $zipCodeCode];
		$statement->execute($parameters);

		try {
			$zipCode = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$zipCode = new ZipCode($row["zipCodeCode"], $row["zipCodeArea"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($zipCode);
	}

	/**
	 * gets all zipCodes
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of zipcodes found, returns null if empty
	 * @throws \PDOException if an error regarding the php data object occurs
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllZipCodes(\PDO $pdo) {
		$query = "SELECT zipCodeCode, zipCodeArea FROM zipCode";
		$statement = $pdo->prepare($query);
		$statement->execute();
		$zipCodes = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$zipCode = new ZipCode($row["zipCodeCode"], $row["zipCodeArea"]);
				$zipCodes[$zipCodes->key()] = $zipCode;
				$zipCodes->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($zipCodes);
	}


}