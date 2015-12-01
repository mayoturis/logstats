<?php

use Logstats\Services\Factories\UserFactory;

class UserFactoryTest extends TestCase {
	public function test_make_creates_user() {
		$userFactory = new UserFactory();
		$user = $userFactory->make(1, 'name', 'password', 'email', 'token');

		$this->assertEquals(1, $user->getId());
		$this->assertEquals('name', $user->getName());
		$this->assertEquals('password', $user->getPassword());
		$this->assertEquals('email', $user->getEmail());
		$this->assertEquals('token', $user->getRememberToken());
	}
}