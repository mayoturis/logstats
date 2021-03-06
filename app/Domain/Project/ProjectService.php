<?php  namespace Logstats\Domain\Project;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Domain\Record\RecordServiceInterface;
use Logstats\Domain\User\User;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\RoleTypes;

class ProjectService implements ProjectServiceInterface {

	/**
	 * @var ProjectRepository
	 */
	private $repository;
	/**
	 * @var RecordServiceInterface
	 */
	private $recordService;
	/**
	 * @var LevelEmailAlertingRepository
	 */
	private $levelEmailAlertingRepository;

	/**
	 * @param ProjectRepository $repository
	 */
	public function __construct(ProjectRepository $repository,
								RecordServiceInterface $recordService,
								LevelEmailAlertingRepository $levelEmailAlertingRepository) {
		$this->repository = $repository;
		$this->recordService = $recordService;
		$this->levelEmailAlertingRepository = $levelEmailAlertingRepository;
	}

	/**
	 * Creates project
	 *
	 * @param string $name Name of the project
	 * @param User $user User which is creating the project
	 * @return Project
	 */
	public function createProject($name, User $user) {
		$writeToken = $this->uniqueWriteTokenForName($name);
		$readToken = $this->uniqueReadTokenForName($name);
		$project = new Project($name, $writeToken, $readToken);
		$project->setCreatedAt(Carbon::now());

		$this->repository->save($project);

		$this->addUserToProject($project, $user, new Role(RoleTypes::ADMIN));

		return $project;
	}

	private function uniqueWriteTokenForName($name) {
		return 'w' . $this->uniqueTokenForName($name);
	}

	private function uniqueReadTokenForName($name) {
		return 'r' . $this->uniqueTokenForName($name);
	}


	/**
	 * Creates unique token for project by its name
	 *
	 * @param string $name
	 * @return string
	 */
	private function uniqueTokenForName($name) {
		return Str::random(20);
	}

	/**
	 * Adds new user to the project
	 *
	 * @param Project $project
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addUserToProject(Project $project, User $user, Role $role) {
		$this->repository->addUserToProject($project, $user, $role);
	}

	/**
	 * Deletes project and all things related to it
	 *
	 * @param Project $project
	 */
	public function deleteProject(Project $project) {
		$this->recordService->deleteRecordsForProject($project);
		$this->levelEmailAlertingRepository->deleteForProject($project->getId());
		$this->repository->delete($project);
	}

	/**
	 * Deletes all roles for user in all projects
	 *
	 * @param User $user
	 * @return mixed
	 */
	public function deleteProjectRolesForUser(User $user) {
		$this->repository->deleteProjectRolesForUser($user);
	}
}