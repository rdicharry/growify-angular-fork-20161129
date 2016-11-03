<?php
/**
 * Plant Information Class
 *
 * This Plant will access and store data. This can be extended to include further attributes that may be needed.
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class Plant{
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
	 * accessor method for plantId
	 * @return int
	 */
	public function getPlantId() {
		return $this->plantId;
	}

	/**
	 * accessor method for plantName
	 * @return string
	 */
	public function getPlantName() {
		return $this->plantName;
	}

	/**
	 * accessor method for plantVariety
	 * @return string
	 */
	public function getPlantVariety() {
		return $this->plantVariety;
	}

	/**
	 * accessor method for plantDescription
	 * @return string
	 */
	public function getPlantDescription() {
		return $this->plantDescription;
	}

	/**
	 * accessor method for plantType
	 * @return string
	 */
	public function getPlantType() {
		return $this->plantType;
	}

	/**
	 * accessor method for plantSpread
	 * @return float
	 */
	public function getPlantSpread() {
		return $this->plantSpread;
	}

	/**
	 * accessor method for plantDaysToHarvest
	 * @return int
	 */
	public function getPlantDaysToHarvest() {
		return $this->plantDaysToHarvest;
	}

	/**
	 * accessor method for plantHeight
	 * @return float
	 */
	public function getPlantHeight() {
		return $this->plantHeight;
	}

	/**
	 * accessor method for plantMinTemp
	 * @return int
	 */
	public function getPlantMinTemp() {
		return $this->plantMinTemp;
	}

	/**
	 * accessor method for plantMaxTemp
	 * @return int
	 */
	public function getPlantMaxTemp() {
		return $this->plantMaxTemp;
	}

	/**
	 * accessor method for plantSoilMoisture
	 * @return string
	 */
	public function getPlantSoilMoisture() {
		return $this->plantSoilMoisture;
	}

	/**
	 * mutator method for plantId
	 * @param int|null $newPlantId new value of plant id
	 * @throws \RangeException if $newPlantId is negative
	 * @throws \TypeError if $newPlantId is not an integer
	 */
	public function setPlantId(int $newPlantId = null) {
		// if the plant id is null, this is a new plant without an id from mySQL
		if($newPlantId === null) {
			$this->plantId = null;
			return;
		}
		// verify that plant id is positive
		if($newPlantId <= 0) {

		}
		$this->plantId = $newPlantId;
	}

	/**
	 * mutator method for plantName
	 * @param string $newPlantName new value of plant name
	 */
	public function setPlantName($newPlantName) {
		$this->plantName = $newPlantName;
	}

	/**
	 * mutator method for plantVariety
	 * @param string $newPlantVariety  new value of plant variety
	 */
	public function setPlantVariety($newPlantVariety) {
		$this->plantVariety = $newPlantVariety;
	}

	/**
	 * mutator method for plantDescription
	 * @param string $newPlantDescription new value of plant description
	 */
	public function setPlantDescription($newPlantDescription) {
		$this->plantDescription = $newPlantDescription;
	}

	/**
	 * mutator method for plantType
	 * @param string $newPlantType new value of plant type
	 */
	public function setPlantType($newPlantType) {
		$this->plantType = $newPlantType;
	}

	/**
	 * mutator method for plantSpread
	 * @param float $newPlantSpread new value of plant spread
	 */
	public function setPlantSpread($newPlantSpread) {
		$this->plantSpread = $newPlantSpread;
	}

	/**
	 * mutator method for plantDaysToHarvest
	 * @param int $newPlantDaysToHarvest new value of plant days to harvest
	 */
	public function setPlantDaysToHarvest($newPlantDaysToHarvest) {
		$this->plantDaysToHarvest = $newPlantDaysToHarvest;
	}

	/**
	 * mutator method for plantHeight
	 * @param float $newPlantHeight new value of plant mature height
	 */
	public function setPlantHeight($newPlantHeight) {
		$this->plantHeight = $newPlantHeight;
	}

	/**
	 * mutator method for plantMinTemp
	 * @param int $newPlantMinTemp new value of plant min temp
	 */
	public function setPlantMinTemp($newPlantMinTemp) {
		$this->plantMinTemp = $newPlantMinTemp;
	}

	/**
	 * mutator method for plantMaxTemp
	 * @param int $newPlantMaxTemp new value of plant max temp
	 */
	public function setPlantMaxTemp($newPlantMaxTemp) {
		$this->plantMaxTemp = $newPlantMaxTemp;
	}

	/**
	 * mutator method for plantSoilMoisture
	 * @param string $newPlantSoilMoisture new value of plant soil moisture requirement
	 */
	public function setPlantSoilMoisture($newPlantSoilMoisture) {
		$this->plantSoilMoisture = $newPlantSoilMoisture;
	}
}