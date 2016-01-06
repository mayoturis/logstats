<?php  namespace Logstats\Domain\User;


class UserService implements UserServiceInterface{

	private $repository;

	/**
	 * @param UserRepository $repository
	 */
	public function __construct(UserRepository $repository) {
		$this->repository = $repository;
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
	public function addRoleToUser(User $user, Role $role) {
		$user->setRole($role);
		$this->repository->save($user);
	}
}