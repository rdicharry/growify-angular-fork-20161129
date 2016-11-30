<?php
/**
 * Location class to use to cross-reference user's zip code location to a latitude/longitude
 * in order to access weather data
 */
namespace Edu\Cnm\Growify;
require_once('autoload.php');

class Location implements \JsonSerializable{
	/**
	 * @var String five digit NM zip code.
	 */
	private $locationZipCode;

	/**
	 * @var float latitude corresponding to this zip code.
	 */
	private $locationLatitude;

	/**
	 * @var float longitude corresponding to this zip code
	 */
	private $locationLongitude;

	private static $US_MAX_LATITUDE = 71.388889;
	private static $US_MIN_LATITUDE = 18.910833;
	private static $US_MAX_LONGITUDE = -66.949778;
	private static $US_MIN_LONGITUDE =  -179.148611;

	public function __construct($locationZipCode, $locationLatitude, $locationLongitude){
		try{
			$this->setLocationZipCode($locationZipCode);
			$this->setLocationLatitude($locationLatitude);
			$this->setLocationLongitude($locationLongitude);
		} catch(\TypeError $te){
			throw(new \TypeError($te->getMessage(), 0, $te));
		} catch(\OutOfBoundsException $oobe){
			throw(new \OutOfBoundsException($oobe->getMessage(), 0, $oobe));
		} catch(\InvalidArgumentException $iae){
			throw(new \InvalidArgumentException($iae->getMessage(), 0, $iae));
		} catch(\Exception $e){
			throw(new \Exception($e->getMessage(), 0, $e));
		}
	}

	public function setLocationZipCode(string $zipCode){
		$zipCode = trim($zipCode);
		if(strlen($zipCode) !== 5){
			throw(new \OutOfBoundsException("zip code must be exactly 5 characters"));
		}
		if(!is_numeric($zipCode)){
			throw(new \InvalidArgumentException("zip code should contain only numeric characters"));
		}
		$this->locationZipCode = $zipCode;
	}

	public function getLocationZipCode(){
		return $this->locationZipCode;
	}

	public function setLocationLatitude(float $latitude){


		if($latitude > self::$US_MAX_LATITUDE || $latitude < self::$US_MIN_LATITUDE){
			throw(new \OutOfBoundsException("not a valid us latitude"));
		}
		$this->locationLatitude = $latitude;
	}

	public function getLocationLatitude(){
		return $this->locationLatitude;
	}

	public function setLocationLongitude(float $longitude){
		if($longitude > self::$US_MAX_LONGITUDE || $longitude < self::$US_MIN_LONGITUDE){
			throw(new \OutOfBoundsException("not a valid us longitude"));
		}
		$this->locationLongitude = $longitude;
	}

	public function getLocationLongitude(){
		return $this->locationLongitude;
	}

	public function insert(\PDO $pdo){
		try{
			$query = "INSERT INTO location(locationZipCode, locationLatitude, locationLongitude) VALUES (:locationZipCode, :locationLatitude, :locationLongitude)";
			$statement = $pdo->prepare($query);
			$parameters = ["locationZipCode"=>$this->locationZipCode,
			"locationLatitude"=>$this->locationLatitude,
			"locationLongitude"=>$this->locationLongitude];
			$statement->execute($parameters);

		} catch(\PDOException $pe){
			throw(new \PDOException($pe->getMessage(), 0, $pe));
		}
	}

	public static function getLocationByZipCode(\PDO $pdo, $zipCode){

		// input sanitization
		$zipCode = trim($zipCode);
		$zipCode = filter_var($zipCode, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($zipCode)){
			throw(new \InvalidArgumentException("invalid zip code"));

		}
		if(strlen($zipCode)!== 5){
			throw(new \InvalidArgumentException("incorrect number of characters in zip code"));
		}

		// create query template
		$query = "SELECT locationLatitude, locationLongitude FROM location WHERE locationZipCode = :locationZipCode";
		$statement = $pdo->prepare($query);
		$parameter = ["locationZipCode"=>$zipCode];
		$statement->execute($parameter);

		// retrieve results of query
		try{
			$location = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$location = new Location($zipCode, $row["locationLatitude"], $row["locationLongitude"]);
			}
		} catch(\Exception $e){
			throw(new \PDOException($e->getMessage(), 0, $e));
		}
		return $location;
	}

	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return($fields);
	}
}