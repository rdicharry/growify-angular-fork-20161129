<?php
require_once(dirname(__DIR__,2)."/vendor/autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use GuzzleHttp\Client;
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
	private $temperatureMin;
	private $temperatureMax;
	private $windSpeed;
	private $timestamp;
	/**
	 * Weather constructor.
	 * @param $temperatureMax float the temperature in fahrenheit
	 * @param $temperatureMin float the temeprature in fahrenheit
	 * @param $windSpeed float the wind speed in miles per hour
	 * @param $timestamp int the time associated with this forecast.
	 * @throws Exception
	 */
	public function __construct($temperatureMin, $temperatureMax, $windSpeed,int $timestamp){
		try{
			$this->setTemperatureMax($temperatureMax);
			$this->setTemperatureMin($temperatureMin);
			$this->setWindSpeed($windSpeed);
			$this->setTimestamp($timestamp);
		} catch(\InvalidArgumentException $iae){
			throw(new \InvalidArgumentException($iae->getMessage(), 0, $iae));
		} catch(\RangeException $re){
			throw(new \RangeException($re->getMessage(), 0, $re));
		} catch(\Exception $e){
			throw(new \Exception($e->getMessage(), 0, $e));
		}
	}
	public function getTemperatureMax(){
		return $this->temperatureMax;
	}
	public function setTemperatureMax($newTemp){
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
		$newWindSpeed = filter_var($newWindSpeed, FILTER_VALIDATE_FLOAT);
		if($newWindSpeed === false){
			throw(new \InvalidArgumentException("wind speed must be a floating point number"));
		}
		$this->windSpeed = floatval($newWindSpeed);
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
		$dailyForecast = $result["daily"];
		$data = $dailyForecast["data"];
		$temperatureMax = $data[0]["temperatureMax"];
		$timestamp = $data[0]["time"];
		$temperatureMin = $data[0]["temperatureMin"];
		$windSpeed = $data[0]["windSpeed"];
		//echo "tmin = ".$temperatureMin ." tmax = ".$temperatureMax ." wind = ".$windSpeed . " time = ".$timestamp;
		$newWeather = new \Weather($temperatureMin, $temperatureMax, $windSpeed, $timestamp);
		return $newWeather;
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