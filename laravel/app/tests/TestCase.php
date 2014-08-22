<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	public function seedDefaultUser () {
		$user = new User;
		$user->username = 'seededUser';
		$user->email = 'info@y-designs.com';
		$user->password = 'password';
		$user->password_confirmation = 'password';
		$user->confirmed = 1;
		$user->save();

		$this->be( $user );
		$this->seededUser = $user;
	}

	/**
	 *	When setting up the tests, seed a default user that will be accesible
	 *  through Auth
	 */
	public function setUp () {
		parent::setUp();
		$this->seedDefaultUser();
	}

	/**
	 *	After testing, remove the seeded user
	 */
	public function tearDown() {
        parent::tearDown();
        $this->seededUser->delete();
    }
}
