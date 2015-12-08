<?php
use Logstats\Entities\User;
use Logstats\ValueObjects\Role;

class UserTest extends TestCase {
	public function test_id_can_be_set_and_get_in_new_user() {
		$user = $this->getUser();
		$user->setId(5);

		$this->assertEquals(5, $user->getId());
	}

	public function test_setId_throws_exception_if_id_is_set() {
		$user = $this->getUser();

		try {
			$user->setId(5);
			$user->setId(6);
			$this->fail('Set id should throw exception');
		} catch(\BadMethodCallException $ex) {
			$this->assertTrue(true);
		}
	}

	public function test_getAuthIdentifier_returns_id() {
		$user = $this->getFullUser();

		$this->assertEquals(1, $user->getAuthIdentifier());
	}

	public function test_getAuthPassword_returns_password() {
		$user = $this->getFullUser();

		$this->assertEquals('password', $user->getAuthPassword());
	}

	public function test_rememberToken_can_be_set_and_get() {
		$user = $this->getUser();
		$user->setRememberToken('token');

		$this->assertEquals('token', $user->getRememberToken());
	}

	public function test_password_can_be_set_and_get() {
		$user = $this->getUser();
		$user->setPassword('password');

		$this->assertEquals('password', $user->getPassword());
	}

	public function test_name_can_be_set_and_get() {
		$user = $this->getUser();
		$user->setName('namo');

		$this->assertEquals('namo', $user->getName());
	}

	public function test_email_can_be_set_and_get() {
		$user = $this->getUser();
		$user->setEmail('email');

		$this->assertEquals('email', $user->getEmail());
	}

	public function test_role_can_be_set_and_get() {
		$user = $this->getuser();
		$role = new Role('role');
		$user->setRole($role);

		$this->assertEquals($role, $user->getRole());
	}

	private function getUser() {
		return new User(null, null, null, null);
	}

	private function getFullUser() {
		$user = new User('name', 'password', 'email', 'token');
		$user->setId(1);
		return $user;
	}

	private function getCollectionMock() {
		return Mockery::mock('\Illuminate\Support\Collection');
	}
}