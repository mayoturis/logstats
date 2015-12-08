<?php

use Logstats\Services\Entities\UserService;

class UserServiceTest extends TestCase {
	public function test_createUser_creates_user() {
		$repository = $this->getRepositoryMock();
		$factory = $this->getFactoryMock();
		$userService = new UserService($repository, $factory);
		$name = 'name';
		$password = 'password';
		$email = 'email';

		$user = Mockery::mock('\Logstats\Entities\User');

		$factory->shouldReceive('make')->once()->andReturn($user);
		$repository->shouldReceive('save')->once()->with($user);

		$this->assertEquals($user, $userService->createUser($name, $password, $email));
	}

	public function test_addRoleToUser_adds_role_to_user() {
		$repository = $this->getRepositoryMock();
		$factory = $this->getFactoryMock();
		$userService = new UserService($repository, $factory);

		$user = Mockery::mock('\Logstats\Entities\User');
		$role = Mockery::mock('\Logstats\ValueObjects\Role');

		$user->shouldReceive('setRole')->with($role);
		$repository->shouldReceive('save')->with($user);

		$userService->addRoleToUser($user, $role);
	}

	private function getRepositoryMock() {
		return Mockery::mock('\Logstats\Repositories\Contracts\UserRepository');
	}

	private function getFactoryMock() {
		return Mockery::mock('\Logstats\Services\Factories\UserFactoryInterface');
	}
}