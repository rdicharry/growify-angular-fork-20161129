<?php
namespace Edu\Cnm\Growify;

require_once("autoload.php");

/**
 * Profile Information Class
 *
 * This Profile will access and store data.
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class Profile implements \JsonSerializable {
	/**
	 * id for this profile; this is the primary key
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * user name for this profile
	 * @var string $profileUsername
	 **/
	private $profileUsername;
	/**
	 * email for this profile
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * zip code for this profile
	 * @var string $profileZipCode
	 **/
	private $profileZipCode;
	/**
	 * hash for this profile
	 * @var string $profileHash
	 **/
	private $profileHash;
	/**
	 * salt for this profile
	 * @var string $profileSalt
	 **/
	private $profileSalt;
	/**
	 * activation code for this profile
	 * @var string $profileActivation
	 **/
	private $profileActivation;

	/**
	 * Profile constructor.
	 * @param $newProfileId
	 * @param $newProfileUsername
	 * @param $newProfileEmail
	 * @param $newProfileZipCode string linking a zip code and a planting area.
	 * @param $newProfileHash
	 * @param $newProfileSalt
	 * @param $newProfileActivation
	 * @throws \Exception
	 * @throws \TypeError
	 */
	public function __construct($newProfileId, $newProfileUsername, $newProfileEmail, $newProfileZipCode, $newProfileHash, $newProfileSalt, $newProfileActivation) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileUsername($newProfileUsername);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileZipCode($newProfileZipCode);
			$this->setProfileHash($newProfileHash);
			$this->setProfileSalt($newProfileSalt);
			$this->setProfileActivation($newProfileActivation);
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
	 * accessor method for profile id
	 * @return int
	 **/
	public function getProfileId() {
		return $this->profileId;
	}

	/**
	 * mutator method for profile id
	 * @param int $newProfileId
	 * @throws \RangeException if $newProfileId is negative
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setProfileId($newProfileId) {
		// if the profile id is null, this is a new profile without an id from mySQL
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		// verify that profile id is positive
		if($newProfileId <= 0) {
			throw (new \RangeException("profile id is not positive"));
		}
		$this->profileId = $newProfileId;
	}

	/**
	 * accessor method for user name
	 * @return string
	 **/
	public function getProfileUsername() {
		return $this->profileUsername;
	}

	/**
	 * mutator method for profile user name
	 * @param string $newProfileUsername
	 * @throws \InvalidArgumentException if $newProfileUsername is empty or is not a string
	 * @throws \RangeException if $newProfileUsername is too long
	 **/
	public function setProfileUsername($newProfileUsername) {
		$newProfileUsername = trim($newProfileUsername);
		$newProfileUsername = filter_var($newProfileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileUsername)) {
			throw (new \InvalidArgumentException("user name is empty or has invalid contents"));
		}
		if(strlen($newProfileUsername) > 24) {
			throw(new \RangeException("user name is too large"));
		}
		$this->profileUsername = $newProfileUsername;
	}

	/**
	 * accessor method for email
	 * @return string
	 **/
	public function getProfileEmail() {
		return $this->profileEmail;
	}

	/**
	 * mutator method for profile email
	 * @param string $newProfileEmail
	 * @throws \InvalidArgumentException if $newProfileEmail is empty or is not a string
	 * @throws \RangeException if $newProfileEmail is too long
	 **/
	public function setProfileEmail($newProfileEmail) {
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileEmail)) {
			throw (new \InvalidArgumentException("email is empty or has invalid contents"));
		}
		if(strlen($newProfileEmail) > 160) {
			throw(new \RangeException("email is too large"));
		}
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for zip code
	 * @return string
	 **/
	public function getProfileZipCode() {
		return $this->profileZipCode;
	}

	/**
	 * mutator method for profile zip code
	 * @param string $newProfileZipCode
	 **/
	public function setProfileZipCode($newProfileZipCode) {
		$this->profileZipCode = $newProfileZipCode;
	}

	/**
	 * accessor method for password hash
	 * @return string
	 **/
	public function getProfileHash() {
		return $this->profileHash;
	}

	/**
	 * mutator method for profile password hash
	 * @param string $newProfileHash
	 **/
	public function setProfileHash($newProfileHash) {
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);


		if(ctype_xdigit($newProfileHash) === false) {
			throw (new \InvalidArgumentException("hash is empty or has invalid contents"));
		}
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("hash length ". strlen($newProfileHash) .", is incorrect length"));
		}
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for password salt
	 * @return string
	 **/
	public function getProfileSalt() {
		return $this->profileSalt;
	}

	/**
	 * mutator method for profile password salt
	 * @param string $newProfileSalt
	 **/
	public function setProfileSalt($newProfileSalt) {
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);

		if(ctype_xdigit($newProfileSalt) === false) {
			throw (new \InvalidArgumentException("salt is empty or has invalid contents"));
		}
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("salt is incorrect length"));
		}
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * accessor method for profile activation state
	 * @return string
	 **/
	public function getProfileActivation() {
		return $this->profileActivation;
	}

	/**
	 * mutator method for profile activation code
	 * @param string $newProfileActivation
	 **/
	public function setProfileActivation($newProfileActivation) {
		$newProfileActivation = trim($newProfileActivation);
		$newProfileActivation = strtolower($newProfileActivation);

		if(ctype_xdigit($newProfileActivation) === false) {
			throw (new \InvalidArgumentException("activation is empty or has invalid contents"));
		}
		if(strlen($newProfileActivation) !== 16) {
			throw(new \RangeException("activation length ". strlen($newProfileActivation)." is incorrect length"));
		}
		$this->profileActivation = $newProfileActivation;
	}

	/**
	 * Insert a new Profile entry.
	 * @param \PDO $pdo the PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function insert(\PDO $pdo) {
		//check to make sure this profile doesn't already exist
		if($this->profileId !== null) {
			throw(new \PDOException("not a new profile"));
		}

		//create query template
		$query = "INSERT INTO profile( profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation) VALUES ( :profileUsername, :profileEmail, :profileZipCode, :profileHash, :profileSalt, :profileActivation)";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders in the template
		$parameters = ["profileUsername" => $this->profileUsername, "profileEmail" => $this->profileEmail, "profileZipCode" => $this->profileZipCode, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt, "profileActivation" => $this->profileActivation];
		$statement->execute($parameters);

		$this->profileId = intval($pdo->lastInsertId());

	}

	/**
	 * Delete a Profile entry.
	 * @param \PDO $pdo PDO connection object.
	 * @throws \PDOException if mySQL related errors occur.
	 * @throws \TypeError if $pdo is not a PDO object.
	 **/
	public function delete(\PDO $pdo) {
		// create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholder in template
		$parameters = ["profileId" => $this->profileId];
		$statement->execute($parameters);
	}

	/**
	 * Updates the Profile entry in mySQL.
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object.
	 **/
	public function update(\PDO $pdo) {
		//create query template
		$query = "UPDATE profile SET profileUsername = :profileUsername, profileEmail = :profileEmail, profileZipCode = :profileZipCode, profileHash = :profileHash, profileSalt = :profileSalt, profileActivation = :profileActivation";
		$statement = $pdo->prepare($query);

		// bind member variables to placeholders
		$parameters = ["profileUsername" => $this->profileUsername, "profileEmail" => $this->profileEmail, "profileZipCode" => $this->profileZipCode, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt, "profileActivation" => $this->profileActivation];
		$statement->execute($parameters);
	}

	/**
	 * Get profile associated with the specified profile Id.
	 * @param \PDO $pdo a PDO connection object
	 * @param int $profileId a valid profile Id
	 * @return Profile|null Profile found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getProfileByProfileId(\PDO $pdo, int $profileId) {
		if($profileId <= 0) {
			throw(new \RangeException("profile id must be positive."));
		}
		// create query template
		$query = "SELECT profileId, profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);

		// bind the profile id to the place holder in the template
		$parameters = ["profileId" => $profileId];
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileUsername"], $row["profileEmail"], $row["profileZipCode"], $row["profileHash"], $row["profileSalt"], $row["profileActivation"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * Get all profiles associated with the specified username.
	 * @param \PDO $pdo a PDO connection object
	 * @param string $profileUsername name of profile being searched for
	 * @return \SplFixedArray SplFixedArray of Profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getProfileByProfileUsername(\PDO $pdo, string $profileUsername) {
		$profileUsername = trim($profileUsername);
		$profileUsername = filter_var($profileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileUsername)) {
			throw (new \InvalidArgumentException("profile username is invalid"));
		}
		// create query template
		$query = "SELECT profileId, profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation FROM profile WHERE profileUsername LIKE :profileUsername";
		$statement = $pdo->prepare($query);

		// bind the username to the place holder in the template
		$parameters = ["profileUsername" => $profileUsername];
		$statement->execute($parameters);

		// build an array of profiles
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileUsername"], $row["profileEmail"], $row["profileZipCode"], $row["profileHash"], $row["profileSalt"], $row["profileActivation"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profiles);
	}

	/**
	 * Get all profiles associated with the specified profile zipcode.
	 * @param \PDO $pdo a PDO connection object
	 * @param string $profileZipcode  of profiles being searched for
	 * @return \SplFixedArray SplFixedArray of Profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getProfileByZipcode(\PDO $pdo, string $profileZipcode) {

		// create query template
		$query = "SELECT profileId, profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation FROM profile WHERE profileZipcode = :profileZipcode";
		$statement = $pdo->prepare($query);

		// bind the profile type to the place holder in the template
		$parameters = ["profileZipcode" => $profileZipcode];
		$statement->execute($parameters);

		// build an array of profiles
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileUsername"], $row["profileEmail"], $row["profileZipCode"], $row["profileHash"], $row["profileSalt"], $row["profileActivation"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profiles);
	}

	/**
	 * Get all profiles associated with the specified profile activation code.
	 * @param \PDO $pdo a PDO connection object
	 * @param string $profileActivation zipcode of profiles being searched for
	 * @return Profile profile the profile that was found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \InvalidArgumentException when activation is empty or has invalid contents
	 * @throws \TypeError when parameters are not the correct data type.
	 **/
	public static function getProfileByProfileActivation(\PDO $pdo, string $profileActivation) {
		$profileActivation = trim($profileActivation);
		$profileActivation = strtolower($profileActivation);

		if(ctype_xdigit($profileActivation) === false) {
			throw (new \InvalidArgumentException("activation is empty or has invalid contents"));
		}
		// create query template
		$query = "SELECT profileId, profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation FROM profile WHERE profileActivation LIKE :profileActivation";
		$statement = $pdo->prepare($query);

		// bind the username to the place holder in the template
		$parameters = ["profileActivation" => $profileActivation];
		$statement->execute($parameters);

		// grab the profile from mySQL
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileUsername"], $row["profileEmail"], $row["profileZipCode"], $row["profileHash"], $row["profileSalt"], $row["profileActivation"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * Get all Profile objects.
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray of Profile objects found or null if none found.
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type.
	 **/
	public static function getAllProfiles(\PDO $pdo) {
		//create query template
		$query = "SELECT profileId, profileUsername, profileEmail, profileZipCode, profileHash, profileSalt, profileActivation FROM profile";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of profile entries
		$profiles = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileUsername"], $row["profileEmail"], $row["profileZipCode"], $row["profileHash"], $row["profileSalt"], $row["profileActivation"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($profiles);
	}

	/**
	 * format state variables for JSON serialization
	 * @return array an array with serialized state variables
	 **/
	public function jsonSerialize() {
		array_push($fields, $this->getProfileId(),$this->getProfileUsername(),$this->getProfileEmail(),$this->getProfileZipCode(),$this->getProfileActivation());
		return ($fields);
	}
}