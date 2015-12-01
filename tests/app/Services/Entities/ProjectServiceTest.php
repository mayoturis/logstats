<?php
use Logstats\Services\Entities\ProjectService;
use Logstats\ValueObjects\Role;
use Logstats\ValueObjects\RoleTypes;

class ProjectServiceTest extends TestCase{
	public function test_createProject_creates_project_and_adds_admin() {
		$pr = $this->getProjectRepository();
		$ur = $this->getUserRepository();
		$pf = $this->getProjectFactory();

		$ps = $this->getMock(ProjectService::class, ['addUserToProject'], [$pr, $pf, $ur]);
		$project = $this->getProject();
		$user = $this->getUser();

		$pf->shouldReceive('make')->once()->andReturn($project);
		$pr->shouldReceive('save')->once()->with($project);
		$ps->expects($this->once())->method('addUserToProject');

		$this->assertEquals($project, $ps->createProject('name', $user));
	}

	public function test_addUserToProject_calls_repository() {
		$pr = $this->getProjectRepository();
		$ur = $this->getUserRepository();
		$pf = $this->getProjectFactory();

		$ps = new ProjectService($pr,$pf,$ur);
		$user = $this->getUser();
		$project = $this->getProject();
		$role = $this->getRole();
		$pr->shouldReceive('addUserToProject')->once();
		$ps->addUserToProject($project, $user, $role);
	}

	private function getProjectRepository() {
		return Mockery::mock('\Logstats\Repositories\Contracts\ProjectRepository');
	}

	private function getUserRepository() {
		return Mockery::mock('\Logstats\Repositories\Contracts\UserRepository');
	}

	private function getProjectFactory() {
		return Mockery::mock('\Logstats\Services\Factories\ProjectFactory');
	}

	private function getProject() {
		return Mockery::mock('\Logstats\Entities\Project');
	}

	/**
	 * @return \Mockery\MockInterface
	 */
	private function getUser() {
		$user = Mockery::mock('\Logstats\Entities\User');
		return $user;
	}

	private function getRole() {
		return Mockery::mock('\Logstats\ValueObjects\Role');
	}
}