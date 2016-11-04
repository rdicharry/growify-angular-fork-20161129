<?php
/**
 * Profile Information Class
 *
 * This Profile will access and store data.
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 ***/
class Profile {
	/**
	 * id for this profile; this is the primary key
	 * @var int $profileId
	 ***/
	private $profileId;
	/**
	 * user name for this profile
	 * @var string $profileUserName
	 ***/
	private $profileUserName;
	/**
	 * email for this profile
	 * @var string $profileEmail
	 ***/
	private $profileEmail;
	/**
	 * zip code for this profile
	 * @var int $profileZipCode
	 ***/
	private $profileZipCode;
	/**
	 * hash for this profile
	 * @var string $profileHash
	 ***/
	private $profileHash;
	/**
	 * salt for this profile
	 * @var string $profileSalt
	 ***/
	private $profileSalt;
	 /**
	 * activation for this profile
	 * @var bool $profileActivation
	 ***/
	private $profileActivation;

	public function _construct($newProfileId, $newProfileUserName, $newProfileEmail, $newProfileZipCode, $newProfileHash, $newProfileSalt, $newProfileActivation){
		try{
			$this->setProfileId($newProfileId);
			$this->setProfileUserName($newProfileUserName);
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
	 * accessor method for user name
	 * @return string
	 ***/
	public function getProfileUserName() {
		return $this->profileUserName;
	}

	/**
	 * accessor method for email
	 * @return string
	 **/
	public function getProfileEmail() {
		return $this->profileEmail;
	}

	/**
	 * accessor method for zip code
	 * @return int
	 **/
	public function getProfileZipCode() {
		return $this->profileZipCode;
	}

	/**
	 * accessor method for password hash
	 * @return string
	 **/
	public function getProfileHash() {
		return $this->profileHash;
	}

	/**
	 * accessor method for password salt
	 * @return string
	 **/
	public function getProfileSalt(){
		return $this->profileSalt;
	}
	/**
	 * accessor method for profile activation state
	 * @return bool
	 **/
	public function getProfileActivation(){
		return $this->profileActivation;
	}

	/**
	 * mutator method for profile id
	 * @param int $newProfileId
	 * @throws \RangeException if $newProfileId is negative
	 * @throws \TypeError if $newProfileId is not an integer
	 **/
	public function setProfileId($newProfileId) {
		// if the plant id is null, this is a new plant without an id from mySQL
		if($newProfileId === null) {
			$this->profileId = null;
			return;
		}
		// verify that plant id is positive
		if($newProfileId <= 0) {
			throw (new \RangeException("plant id is not positive"));
		}
		$this->profileId = $newProfileId;
	}

	/**
	 * mutator method for profile user name
	 * @param string $newProfileUserName
	 * @throws \InvalidArgumentException if $newProfileUserName is empty or is not a string
	 * @throws \RangeException if $newProfileUserName is too long
	 **/
	public function setProfileUserName($newProfileUserName) {
		$newProfileUserName = trim($newProfileUserName);
		$newProfileUserName = filter_var($newProfileUserName,FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileUserName)){
			throw (new \InvalidArgumentException("user name is empty or has invalid contents"));
		}
		if(strlen($newProfileUserName) > 24) {
			throw(new \RangeException("user name is too large"));
		}
		$this->profileUserName = $newProfileUserName;
	}

	/**
	 * mutator method for profile email
	 * @param string $newProfileEmail
	 * @throws \InvalidArgumentException if $newProfileEmail is empty or is not a string
	 * @throws \RangeException if $newProfileEmail is too long
	 **/
	public function setProfileEmail($newProfileEmail) {
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail,FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileEmail)){
			throw (new \InvalidArgumentException("email is empty or has invalid contents"));
		}
		if(strlen($newProfileEmail) > 160) {
			throw(new \RangeException("email is too large"));
		}
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * mutator method for profile zip code
	 * @param int $newProfileZipCode
	 * @throws \UnexpectedValueException if $newProfileZipCode is not an int
	 * @throws \RangeException if $newProfileZipCode is not positive
	 **/
	public function setProfileZipCode($newProfileZipCode) {
		$newProfileZipCode = filter_var($newProfileZipCode,FILTER_VALIDATE_INT);
		if($newProfileZipCode === false){
			throw (new \UnexpectedValueException("zip code is not a valid int"));
		}
		if($newProfileZipCode >= 0){
			throw (new \RangeException("zip code is not positive"));
		}
		$this->profileZipCode = $newProfileZipCode;
	}

	/**
	 * mutator method for profile password hash
	 * @param string $newProfileHash
	 **/
	public function setProfileHash($newProfileHash) {
		$this->profileHash = $newProfileHash;
	}

	/**
	 * mutator method for profile password salt
	 * @param string $newProfileSalt
	 ***/
	public function setProfileSalt($newProfileSalt) {
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * mutator method for profile activation state
	 * @param boolean $newProfileActivation
	 ***/
	public function setProfileActivation($newProfileActivation) {
		$this->profileActivation = $newProfileActivation;
	}
}