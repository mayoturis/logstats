<?php

use Logstats\Domain\Project\ProjectServiceInterface;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\User;
use Logstats\Domain\User\UserRepository;
use Logstats\Domain\User\UserService;

class UserServiceTest extends TestCase {
	public function test_createUser_creates_user() {
		$repository = $this->getRepositoryMock();
		$projectService = $this->getProjectService();
		$userService = new UserService($repository, $projectService);
		$name = 'name';
		$password = 'password';
		$email = 'email';

		$user = Mockery::mock(User::class);

		$repository->shouldReceive('save')->once();

		$userService->createUser($name, $password, $email);
		// $this->assertEquals($user, $userService->createUser($name, $password, $email));
	}

	public function test_addRoleToUser_adds_role_to_user() {
		$repository = $this->getRepositoryMock();
		$projectService = $this->getProjectService();
		$userService = new UserService($repository, $projectService);

		$user = Mockery::mock(User::class);
		$role = Mockery::mock(Role::class);

		$user->shouldReceive('setRole')->with($role);
		$repository->shouldReceive('save')->with($user);

		$userService->setUserRole($user, $role);
	}

	public function test_delete_calls_projecet_service_and_repository() {
		$projectService = $this->getProjectService();
		$repository = $this->getRepositoryMock();
		$userService = new UserService($repository, $projectService);
		$user = Mockery::mock(User::class);
		$projectService->shouldReceive('deleteProjectRolesForUser')->once()->with($user);
		$repository->shouldReceive('delete')->once()->with($user);

		$userService->delete($user);

	}

	private function getRepositoryMock() {
		return Mockery::mock(UserRepository::class);
	}

	private function getProjectService() {
		return Mockery::mock(ProjectServiceInterface::class);
	}
}