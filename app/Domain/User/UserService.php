<?php  namespace Logstats\Domain\User;


use Logstats\Domain\Project\ProjectService;
use Logstats\Domain\Project\ProjectServiceInterface;

class UserService implements UserServiceInterface{

	private $repository;
	private $projectService;

	/**
	 * @param UserRepository $repository
	 */
	public function __construct(UserRepository $repository, ProjectServiceInterface $projectService) {
		$this->repository = $repository;
		$this->projectService = $projectService;
	}

	/**
	 * @param string $name
	 * @param string $password
	 * @param string|null $email
	 * @return User
	 */
	public function createUser($name, $password, $email = null) {
		$user = new User($name,bcrypt($password),$email);
		$this->repository->save($user);

		return $user;
	}

	/**
	 * @param User $user
	 * @param Role $role
	 * @return User
	 */
	public function setUserRole(User $user, Role $role = null) {
		$user->setRole($role);
		$this->repository->save($user);
	}

	public function delete(User $user) {
		$this->projectService->deleteProjectRolesForUser($user);
		$this->repository->delete($user);
	}
}