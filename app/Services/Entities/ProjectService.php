<?php  namespace Logstats\Services\Entities; 

use Carbon\Carbon;
use Logstats\Services\Factories\ProjectFactoryInterface;
use Logstats\Entities\Project;
use Logstats\Entities\User;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Repositories\Contracts\UserRepository;
use Logstats\ValueObjects\Role;
use Logstats\ValueObjects\RoleTypes;

class ProjectService implements ProjectServiceInterface {

	/**
	 * @var ProjectRepository
	 */
	private $repository;
	/**
	 * @var UserRepository
	 */
	private $userRepository;
	/**
	 * @var ProjectFactoryInterface
	 */
	private $factory;

	/**
	 * @param ProjectRepository $repository
	 * @param ProjectFactoryInterface $factory
	 * @param UserRepository $userRepository
	 */
	public function __construct(ProjectRepository $repository, ProjectFactoryInterface $factory, UserRepository $userRepository) {
		$this->repository = $repository;
		$this->userRepository = $userRepository;
		$this->factory = $factory;
	}

	/**
	 * Creates project
	 *
	 * @param string $name Name of the project
	 * @param User $user User which is creating the project
	 * @return \Logstats\Entities\Project
	 */
	public function createProject($name, User $user) {
		$token = $this->uniqueTokenForName($name);
		$project = $this->factory->make(null, $name, $token);

		$this->repository->save($project);

		$this->addUserToProject($project, $user, new Role(RoleTypes::ADMIN));

		return $project;
	}


	/**
	 * Creates unique token for project by its name
	 *
	 * @param string $name
	 * @return string
	 */
	private function uniqueTokenForName($name) {
		return $name . substr(md5(microtime(uniqid())), 0, 10);
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
}