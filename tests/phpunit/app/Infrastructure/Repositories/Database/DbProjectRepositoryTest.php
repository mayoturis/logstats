<?php

use Carbon\Carbon;
use Logstats\Domain\Project\Project;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\User;
use Logstats\Infrastructure\Repositories\Database\DbProjectRepository;
use Logstats\Infrastructure\Repositories\Database\Factories\StdProjectFactory;
use Logstats\Infrastructure\Repositories\Database\Factories\StdUserFactory;
use Logstats\Support\Date\CarbonConvertor;

class DbProjectRepositoryTest extends DatabaseTestCase {

	/**
	 * @var DbProjectRepository
	 */
	private $repository;

	public function setUp() {
		parent::setUp();

		$this->repository = $this->getDbProjectRepository();
	}

	public function test_project_can_be_found_by_id() {
		$project = $this->repository->findById(1);

		$this->assertEquals(1, $project->getId());
		$this->assertEquals('project1', $project->getName());
		$this->assertEquals('writeProject1Token', $project->getWriteToken());
		$this->assertEquals('readProject1Token', $project->getReadToken());
	}

	public function test_findById_returns_null_if_given_id_doesnt_exist() {
		$this->assertNull($this->repository->findById(55));
	}

	public function test_save_inserts_project_if_it_doesnt_exists() {
		$project = new Project('name', 'writeToken', 'readToken');
		$project->setCreatedAt(Carbon::now());

		$this->repository->save($project);

		$this->assertNotEmpty($project->getId());
		$savedProject = $this->repository->findById($project->getId());
		$this->assertEquals('name', $savedProject->getName());
		$this->assertEquals('writeToken', $savedProject->getWriteToken());
		$this->assertEquals('readToken', $savedProject->getReadToken());
		$this->assertEquals($project->getCreatedAt(), $savedProject->getCreatedAt());

		$this->assertEquals(4, count($this->repository->findAll()));
	}

	public function test_save_updates_project_if_it_exists() {
		$project = $this->repository->findById(2);
		$project->setName('newName');
		$project->setWriteToken('newWriteToken');
		$project->setReadToken('newReadToken');

		$this->repository->save($project);

		$updatedProject = $this->repository->findById(2);
		$this->assertEquals('newName', $updatedProject->getName());
		$this->assertEquals('newWriteToken', $updatedProject->getWriteToken());
		$this->assertEquals('newReadToken', $updatedProject->getReadToken());
	}

	public function test_project_can_be_found_by_write_token() {
		$project = $this->repository->findByWriteToken('writeProject2Token');

		$this->assertEquals(2, $project->getId());
		$this->assertEquals('project2', $project->getName());
		$this->assertEquals('writeProject2Token', $project->getWriteToken());
	}

	public function test_project_can_be_found_by_read_token() {
		$project = $this->repository->findByReadToken('readProject2Token');

		$this->assertEquals(2, $project->getId());
		$this->assertEquals('project2', $project->getName());
		$this->assertEquals('readProject2Token', $project->getReadToken());

	}

	public function test_all_project_can_be_found() {
		$this->assertEquals(3, count($this->repository->findAll()));
	}

	public function test_role_for_user_can_be_found_in_project() {
		$user1 = new User('','','');
		$user1->setId(1);
		$user3 = new User('','','');
		$user3->setId(3);
		$project = new Project('','','');
		$project->setId(1);

		$this->assertNull($this->repository->findRoleForUserInProject($user1, $project));
		$this->assertEquals('admin', $this->repository->findRoleForUserInProject($user3, $project));
	}

	public function test_user_can_be_added_to_project() {
		$user = new User('','','');
		$user->setId(1);
		$project = new Project('','','');
		$project->setId(1);

		$this->repository->addUserToProject($project, $user, new Role('datamanager'));

		$this->assertEquals('datamanager', $this->repository->findRoleForUserInProject($user,$project));
	}

	public function test_all_projects_with_latest_records_can_be_found() {
		$projectsDtos = $this->repository->findAllWithLatestRecord();

		$this->assertEquals(3, count($projectsDtos));
		list($project1dto, $project2dto, $project3dto) = $projectsDtos;
		$this->assertNotEmpty($project1dto->getLastRecordDate());
		$this->assertNotEmpty($project2dto->getLastRecordDate());
		$this->assertNotEmpty($project3dto->getLastRecordDate());
		$this->assertTrue($project1dto->getLastRecordDate() instanceof Carbon);
		$this->assertTrue($project2dto->getLastRecordDate() instanceof Carbon);
		$this->assertTrue($project3dto->getLastRecordDate() instanceof Carbon);
		$this->assertEquals(3, $project1dto->getProject()->getId());
		$this->assertEquals(1, $project2dto->getProject()->getId());
		$this->assertEquals(2, $project3dto->getProject()->getId());
	}

	public function test_projects_with_latest_record_for_user_can_be_found() {
		$projectsDtos3 = $this->repository->findAllWithLatestRecord(['admin'], 3);
		$projectsDtos4 = $this->repository->findAllWithLatestRecord(['admin'], 4);

		$this->assertEquals(1, count($projectsDtos3));
		$this->assertEquals('project1', $projectsDtos3[0]->getProject()->getName());

		$this->assertEmpty($projectsDtos4);
	}

	public function test_projectRoleList_can_be_found() {
		$project = new Project('','','');
		$project->setId(1);
		$projectRoleList = $this->repository->getProjectRoleList($project);

		$user1 = new User('','','');
		$user1->setId(1);
		$user3 = new User('','','');
		$user3->setId(3);

		$this->assertNull($projectRoleList->getRoleForUser($user1));
		$this->assertEquals('admin', $projectRoleList->getRoleForUser($user3));
		$this->assertEquals(2, count($projectRoleList->getUsers()));
	}

	public function test_projectRoleList_can_be_saved() {
		$project = new Project('','','');
		$project->setId(1);
		$projectRoleList = $this->repository->getProjectRoleList($project);

		$user = new User('','','');
		$user->setId(1);
		$projectRoleList->setRole($user, new Role('datamanager'));
		$this->repository->saveProjectRoleList($projectRoleList, $project);

		$savedProjectRoleList = $this->repository->getProjectRoleList($project);

		$this->assertEquals(3, count($savedProjectRoleList->getUsers()));
		$this->assertEquals('datamanager', $savedProjectRoleList->getRoleForUser($user));
	}

	public function test_project_roles_can_be_deleted() {
		$project = new Project('','','');
		$project->setId(1);

		$this->repository->deleteProjectRoles($project);
		$projectRoleList = $this->repository->getProjectRoleList($project);

		$this->assertEmpty($projectRoleList->getUsers());
	}


	public function test_user_roles_in_project_can_be_deleted() {
		$user = new User('','','');
		$user->setId(3);
		$project = new Project('','','');
		$project->setId(1);

		$this->repository->deleteProjectRolesForUser($user);
		$this->assertNull($this->repository->findRoleForUserInProject($user,$project));
	}

	private function getDbProjectRepository() {
		$carbonConvertor = new CarbonConvertor();
		return new DbProjectRepository(new StdProjectFactory($carbonConvertor), $carbonConvertor, new StdUserFactory());
	}
}