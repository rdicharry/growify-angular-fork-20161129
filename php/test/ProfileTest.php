<?php
namespace Cnm\Edu\Growify\Test;

use Cnm\Edu\Growify\{Profile, ZipCode};

require_once("GrowifyTest.php");

require_once(dirname(__DIR__)."/classes/autoload.php");
/**
 * ProfileTest Class
 *
 * This Profile Test will test the search for profiles by Id, Username,
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class ProfileTest extends GrowifyTest {
	/**
	 * user name for this profile
	 * @var string $VALID_USERNAME
	 **/
	protected $VALID_USERNAME = "testperson";
	/**
	 * email for this profile
	 * @var string $VALID_EMAIL
	 **/
	protected $VALID_EMAIL = "testperson@gmail.com";
	/**
	 * hash for this profile
	 * just gibberish letters
	 * @var string $VALID_HASH
	 **/
	protected $VALID_HASH = "d7fsdyfsdf79sfds7f7sd87f87dsf7sdf87s87df78ds87f87s87df87dss78f7d8s878f8d7sf7sduihjhjdkskhfkjhsdjkfjkshjfhjdsjkfhkjshdfkjhdsjkfhs";
	/**
	 * salt for this profile
	 * just gibberish letters
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT = "hshfkksauck994544jumfjf8of8jiuxu7uc7ic8biv9vvob8bocjjf778rusudli" ;
	/**
	 * activation for this profile
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION = "jk87289d8fs8d9g9";
	/**
	 * The profile being tested
	 * @var Profile profile
	 **/
	protected $profile = null;
	/**
	 * zipcode for testing
	 * @var ZipCode zipcode
	 **/
	protected $zipcode = null;

	public final function setUp() {
		//run the default setUp() method
		parent::setUp();
		//create new zip code for testing
		$this->zipcode = new ZipCode(87102, "7a");
		$this->zipcode->insert($this->getPDO());
	}
	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileZipCode(), $this->zipcode->getZipCodeCode());
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileActivation(), $this->VALID_ACTIVATION);
	}

	/**
	 * test inserting a Profile that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidProfile() {
		// create a Profile with a non null profile id and watch it fail
		$profile = new Profile(GrowifyTest::INVALID_KEY, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());
	}

	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// edit the Profile and update it in mySQL
		$profile->setProfileUserName($this->VALID_USERNAME);
		$profile->update($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileZipCode(), $this->zipcode->getZipCodeCode());
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileActivation(), $this->VALID_ACTIVATION);
	}

	/**
	 * test updating a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Profile, try to update it without actually inserting it and watch it fail
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->update($this->getPDO());
	}

	/**
	 * test creating a Profile and then deleting it
	 **/
	public function testDeleteValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// delete the Profile from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$profile->delete($this->getPDO());

		// grab the data from mySQL and enforce the Profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertNull($pdoProfile);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
	}

	/**
	 * test deleting a Profile that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidProfile() {
		// create a Profile and try to delete it without actually inserting it
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->delete($this->getPDO());
	}

	/**
	 * test grabbing a Profile by profile name
	 **/
	public function testGetValidProfileByProfileUsername() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getProfileByProfileName($this->getPDO(), $profile->getProfileName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Profile", $results);

		// grab the result from the array and validate it
		$pdoProfile = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileZipCode(), $this->zipcode->getZipCodeCode());
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileActivation(), $this->VALID_ACTIVATION);
	}

	/**
	 * test grabbing a Profile by name that does not exist
	 **/
	public function testGetInvalidProfileByProfileUsername() {
		// grab a profile by searching for name that does not exist
		$profile = Profile::getProfileByProfileName($this->getPDO(), "This is not a username");
		$this->assertCount(0, $profile);
	}

	/**
	 * test grabbing a Profile by profile activation code
	 **/
	public function testGetValidProfileByProfileActivation() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getProfileByProfileActivation($this->getPDO(), $profile->getProfileActivation());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Profile", $results);

		// grab the result from the array and validate it
		$pdoProfile = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileZipCode(), $this->zipcode->getZipCodeCode());
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileActivation(), $this->VALID_ACTIVATION);
	}

	/**
	 * test grabbing a Profile by a type that does not exist
	 **/
	public function testGetInvalidProfileByProfileActivation() {
		// grab a profile by searching for type that does not exist
		$profile = Profile::getProfileByProfileActivation($this->getPDO(), "This is not an activation code");
		$this->assertCount(0, $profile);
	}
	/**
	 * test grabbing all Profiles
	 **/
	public function testGetAllValidProfiles() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert to into mySQL
		$profile = new Profile(null, $this->VALID_USERNAME, $this->VALID_EMAIL, $this->zipcode->getZipCodeCode(), $this->VALID_HASH, $this->VALID_SALT, $this->VALID_ACTIVATION);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Profile::getAllProfiles($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Growify\\Profile", $results);

		// grab the result from the array and validate it
		$pdoProfile = $results[0];
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
		$this->assertEquals($pdoProfile->getProfileZipCode(), $this->zipcode->getZipCodeCode());
		$this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
		$this->assertEquals($pdoProfile->getProfileActivation(), $this->VALID_ACTIVATION);
	}
}