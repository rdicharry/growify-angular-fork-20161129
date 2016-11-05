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
	 * @throws
	 * @throws
	 * @throws
	 */
	public function __construct(int $newGardenProfileId, \DateTime $newGardenDatePlanted, int $newGardenPlantId){

		try{
			$this->setGardenProfileId($newGardenProfileId);
			$this->setGardenDatePlanted($newGardenDatePlanted);
			$this->setGardenPlantId($newGardenPlantId);
		} catch(\InvalidArgumentException ) {
			/* TODO add relevant exceptions, documentation */
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


}