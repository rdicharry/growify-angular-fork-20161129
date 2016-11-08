<?php
namespace Edu\Cnm\Growify\Test;

use Edu\Cnm\Growify\{Profile,ZipCode};

require_once("GrowifyTest.php");

require_once(dirname(__DIR__)."/classes/autoload.php");
/**
 * ProfileTest Class
 *
 * This PlantTest will .
 *
 * @author Greg Bloom <gbloomdev@gmail.com>
 * @version 0.1.0
 **/
class ProfileTest{
	/**
	 * user name for this profile
	 * @var string $profileUsername
	 **/
	protected $VALID_USERNAME;
	/**
	 * email for this profile
	 * @var string $profileEmail
	 **/
	protected $VALID_EMAIL;
	/**
	 * zip code for this profile
	 * @var string $profileZipCode
	 **/
	protected $VALID_ZIPCODE;
	/**
	 * The profile being tested
	 * @var Profile profile
	 **/
	protected $profile = null;

	public final function setUp() {
		//run the default setUp() method
		parent::setUp();

		$zipcode = new ZipCode(87102, );
	}
}