<?php
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Project\ProjectService;
use Logstats\Domain\Record\RecordServiceInterface;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\User;
use Logstats\Domain\User\UserRepository;

class ProjectServiceTest extends TestCase{
	public function test_createProject_creates_project_and_adds_admin() {
		$pr = $this->getProjectRepository();
		$rs = $this->getRecordService();
		$lear = $this->getLevelEmailAlertingRepostirory();

		$ps = $this->getMock(ProjectService::class, ['addUserToProject','uniqueWriteTokenForName', 'uniqueReadTokenForName', 'uniqueTokenForName'], [$pr, $rs, $lear]);
		$user = $this->getUser();

		$pr->shouldReceive('save')->once();
		//$ps->expects($this->once())->method('uniqueTokenForName');
		$ps->expects($this->once())->method('addUserToProject');

		$ps->createProject('name', $user);
	}

	public function test_addUserToProject_calls_repository() {
		$pr = $this->getProjectRepository();
		$rs = $this->getRecordService();
		$lear = $this->getLevelEmailAlertingRepostirory();

		$ps = new ProjectService($pr,$rs,$lear);
		$user = $this->getUser();
		$project = $this->getProject();
		$role = $this->getRole();
		$pr->shouldReceive('addUserToProject')->once();
		$ps->addUserToProject($project, $user, $role);
	}

	public function test_deleteProject_calls_record_service_and_repository() {
		$projectRepository = $this->getProjectRepository();
		$recordService = $this->getRecordService();
		$lear = $this->getLevelEmailAlertingRepostirory();

		$projectService = new ProjectService($projectRepository, $recordService, $lear);
		$project = $this->getProject();

		$recordService->shouldReceive('deleteRecordsForProject')->once()->with($project);
		$projectRepository->shouldReceive('delete')->once()->with($project);
		$project->shouldReceive('getId')->andReturn('some_id');
		$lear->shouldReceive('deleteForProject')->once()->with('some_id');

		$projectService->deleteProject($project);
	}

	public function test_deleteProjectRolesForUser_calls_repository() {
		$projectRepository = $this->getProjectRepository();
		$recordService = $this->getRecordService();
		$lear = $this->getLevelEmailAlertingRepostirory();

		$projectService = new ProjectService($projectRepository, $recordService, $lear);
		$user = $this->getUser();

		$projectRepository->shouldReceive('deleteProjectRolesForUser')->once()->with($user);
		$projectService->deleteProjectRolesForUser($user);
	}


	private function getProjectRepository() {
		return Mockery::mock(ProjectRepository::class);
	}

	private function getUserRepository() {
		return Mockery::mock(UserRepository::class);
	}

	private function getLevelEmailAlertingRepostirory() {
		return Mockery::mock(LevelEmailAlertingRepository::class);
	}

	private function getProject() {
		return Mockery::mock(Project::class);
	}

	/**
	 * @return \Mockery\MockInterface
	 */
	private function getUser() {
		$user = Mockery::mock(User::class);
		return $user;
	}

	private function getRole() {
		return Mockery::mock(Role::class);
	}

	private function getRecordService() {
		return Mockery::mock(RecordServiceInterface::class);
	}
}