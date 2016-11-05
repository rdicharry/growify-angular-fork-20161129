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
}