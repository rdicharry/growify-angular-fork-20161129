<?php
require_once(dirname(__DIR__,2)."/vendor/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use GuzzleHttp\Client;
use Edu\Cnm\Growify\Location;
require_once("autoload.php");

/**
 * Class Weather
 *
 * encapsulate weather (forecast) data and its associated timestamp.
 *
 * currently does not interact with the database (no insert(), update(), delete()
 * methods implemented.
 *
 * @author Rebecca Dicharry <rdicharry@cnm.edu>
 */
class Weather implements JsonSerializable {

	private $currentTemperature; // degrees F
	private $temperatureMin;
	private $temperatureMax;
	private $windSpeed; // mph
	private $humidity;
	private $precipitatonProbability;
	private $timestamp; // seconds since beg. of time, timezone based on current location
	private $summary;
	/**
	 * Weather constructor.
	 * @param $temperatureMax float the temperature in fahrenheit
	 * @param $temperatureMin float the temeprature in fahrenheit
	 * @param $windSpeed float the wind speed in miles per hour
	 * @param $timestamp int the time associated with this forecast.
	 * @throws Exception
	 */
	public function __construct($currentTemperature, $temperatureMin, $temperatureMax, $windSpeed, $humidity, $precipitationProbability, int $timestamp, string $summary){
		try{
			$this->setCurrentTemperature($currentTemperature);
			$this->setTemperatureMax($temperatureMax);
			$this->setTemperatureMin($temperatureMin);
			$this->setWindSpeed($windSpeed);
			$this->setHumidity($humidity);
			$this->setPrecipitationProbability($precipitationProbability);
			$this->setTimestamp($timestamp);
			$this->setSummary($summary);
		} catch(\InvalidArgumentException $iae){
			throw(new \InvalidArgumentException($iae->getMessage(), 0, $iae));
		} catch(\RangeException $re){
			throw(new \RangeException($re->getMessage(), 0, $re));
		} catch(\Exception $e){
			throw(new \Exception($e->getMessage(), 0, $e));
		}
	}
	public function getCurrentTemperature(){
		return $this->currentTemperature;
	}
	public function setCurrentTemperature($newTemp){
		if($newTemp === null){
			$this->currentTemperature = null;
			return;
		}
		$newTemp = filter_var($newTemp, FILTER_VALIDATE_FLOAT);
		if($newTemp === false){
			throw(new \InvalidArgumentException("temperature must be a floating point number"));
		}
		$this->currentTemperature = $newTemp;
	}
	public function getTemperatureMax(){
		return $this->temperatureMax;
	}
	public function setTemperatureMax($newTemp){
		if($newTemp === null){
			$this->temperatureMax = null;
			return;
		}
		$newTemp = filter_var($newTemp, FILTER_VALIDATE_FLOAT);
		if($newTemp === false){
			throw(new \InvalidArgumentException("temperature must be a floating point number"));
		}
		$this->temperatureMax = floatval($newTemp);
	}
	public function getTemperatureMin(){
		return $this->temperatureMin;
	}
	public function setTemperatureMin($newTemp){
		if($newTemp === null){
			$this->temperatureMin = null;
			return;
		}
		$newTemp = filter_var($newTemp, FILTER_VALIDATE_FLOAT);
		if($newTemp === false){
			throw(new \InvalidArgumentException("temperature must be a floating point number"));
		}
		$this->temperatureMin = floatval($newTemp);
	}
	public function getWindSpeed(){
		return $this->windSpeed;
	}
	public function setWindSpeed($newWindSpeed){
		if($newWindSpeed === null){
			$this->windSpeed = null;
			return;
		}
		$newWindSpeed = filter_var($newWindSpeed, FILTER_VALIDATE_FLOAT);
		if($newWindSpeed === false){
			throw(new \InvalidArgumentException("wind speed must be a floating point number"));
		}
		$this->windSpeed = floatval($newWindSpeed);
	}
	public function getHumidity(){
		return $this->humidity;
	}
	public function setHumidity($newHumidity){
		if($newHumidity === null){
			$this->humidity = null;
			return;
		}
		$newHumidity = filter_var($newHumidity, FILTER_VALIDATE_FLOAT);
		if($newHumidity === false){
			throw(new \InvalidArgumentException("humidity must be a floating point number"));
		}
		$this->humidity = floatval($newHumidity);
	}
	public function getPrecipitationProbability(){
		return $this->precipitatonProbability;
	}
	public function setPrecipitationProbability($newProbability){
		if($newProbability === null){
			$this->precipitatonProbability = null;
		}
		$newProbability = filter_var($newProbability, FILTER_VALIDATE_FLOAT);
		if($newProbability === false || $newProbability >1 || $newProbability < 0){
			throw(new \InvalidArgumentException("probability must be a float between 0 and 1"));
		}
	}
	public function getSummary(){
		return $this->summary;
	}
	public function setSummary(string $newSummary){
		if($newSummary === null){
			$this->summary = null;
			return;
		}
		$newSummary = filter_var($newSummary, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if($newSummary === false){
			throw(new \InvalidArgumentException("weather summary should be a string"));
		}
	}
	public function getTimestamp(){
		return $this->timestamp;
	}
	public function setTimestamp(int $time){
		$time = filter_var($time, FILTER_VALIDATE_INT);
		if($time === false ){
			if($time <0) {
				throw(new \InvalidArgumentException("time stamp must be a positive integer"));
			}
		}
		$this->timestamp = $time;
	}
	/**
	 * Get the current daily weather forecast for Albuquerque from darksky.net
	 * @return Weather a weather object with current conditions
	 */
	public static function getCurrentWeatherAlbuquerque(){
		$config = readConfig("/etc/apache2/capstone-mysql/growify.ini");
		$key = $config["darksky"];
		$location = "35.0853,-106.6056";
		$base_url = "https://api.darksky.net/forecast";
		$client = new Client([
			'base_uri'=>$base_url,
			'timeout'=>2.0
		]);
		// send a request to darksky via https
		// I think this blocks on response?
		$response = $client->request('GET', "/forecast/".$key."/".$location);
		$result = json_decode($response->getBody(), true);
		$data = $result["currently"];


		$temperature = $data["temperature"];
		$timestamp = $data["time"];

		$windSpeed = $data["windSpeed"];
		$humidity = $data["humidity"];
		$precipProbability = $data["precipProbability"];
		$summary = $data["summary"];

		//echo "tmin = ".$temperatureMin ." tmax = ".$temperatureMax ." wind = ".$windSpeed . " time = ".$timestamp;
		$newWeather = new \Weather($temperature,null, null, $windSpeed, $humidity, $precipProbability, $timestamp, $summary);
		return $newWeather;
	}

	/**
	 * get current weather for NM zipcode
	 * @param string $zipcode
	 */
	public static function getCurrentWeatherByZipcode(\PDO $pdo, string $zipcode){


		$location = Location::getLocationByZipCode($pdo, $zipcode);

		$config = readConfig("/etc/apache2/capstone-mysql/growify.ini");
		$key = $config["darksky"];
		$exclude = "exclude=minutely,hourly,daily";



		$base_url = "https://api.darksky.net/";
		$client = new Client([
			'base_uri'=>$base_url,
			'timeout'=>2.0
		]);
		// send a request to darksky via https
		// I think this blocks on response?
		// add exclude=["minutely", "hourly"]
		$response = $client->request('GET', "forecast/".$key."/".$location->getLocationLatitude().", ".$location->getLocationLongitude())."?".$exclude;
		$result = json_decode($response->getBody(), true);
		$data = $result["currently"];

		$temperature = $data["temperature"];
		$timestamp = $data["time"];

		$windSpeed = $data["windSpeed"];
		$humidity = $data["humidity"];
		$precipProbability = $data["precipProbability"];
		$summary = $data["summary"];

		// temp min and max not specified - these are only
		// in FORECAST not current weather.
		$temperatureMin = null;
		$temperatureMax = null;

		$newWeather = new \Weather($temperature,null, null, $windSpeed, $humidity, $precipProbability, $timestamp, $summary);
		return $newWeather;
	}

	/**
	 * get a week of weather data by zipcode
	 * return an array of weather objects
	 * @param string $zipcode
	 */
	public static function getWeekForecastWeatherByZipcode(\PDO $pdo, string $zipcode) {
		// add exclude=["minutely", "hourly"]

		$location = Location::getLocationByZipCode($pdo, $zipcode);

		$config = readConfig("/etc/apache2/capstone-mysql/growify.ini");
		$key = $config["darksky"];
		$exclude = "exclude=minutely,hourly";

		$base_url = "https://api.darksky.net/";
		$client = new Client([
			'base_uri' => $base_url,
			'timeout' => 2.0
		]);
		// send a request to darksky via https
		// I think this blocks on response?
		// add exclude=["minutely", "hourly"]
		$response = $client->request('GET', "forecast/" . $key . "/" . $location->getLocationLatitude() . ", " . $location->getLocationLongitude()) . "?" . $exclude;
		$result = json_decode($response->getBody(), true);
		$dailyForecast = $result["daily"];
		$data = $dailyForecast["data"];

		$weekOfWeather = [];

		for($i = 0; $i < count($data); $i++) {
			$temperatureMax = $data[$i]["temperatureMax"];
			$timestamp = $data[$i]["time"];
			$temperatureMin = $data[$i]["temperatureMin"];
			$windSpeed = $data[$i]["windSpeed"];
			$humidity = $data[$i]["humidity"];
			$precipProbability = $data[$i]["precipProbability"];
			$summary = $data[$i]["summary"];
			//echo "tmin = ".$temperatureMin ." tmax = ".$temperatureMax ." wind = ".$windSpeed . " time = ".$timestamp;
			$newWeather = new \Weather(null, $temperatureMin, $temperatureMax, $windSpeed, $humidity, $precipProbability, $timestamp, $summary);
			array_push($weekOfWeather, $newWeather);
		}
		return $weekOfWeather;
	}
	/**
	 * Specifies the JSON serialized version of this object.
	 * @return array array containing all public fields of Weather.
	 */
	function jsonSerialize() {
		return(get_object_vars($this));
		// note - if the date is represented as a DateTime rather than an int timestamp, we will need to do a little more work here.
	}
}