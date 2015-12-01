<?php  namespace Logstats\Services\Entities; 

use Logstats\Services\Factories\UserFactoryInterface;
use Logstats\Entities\User;
use Logstats\Repositories\Contracts\UserRepository;
use Logstats\ValueObjects\Role;

class UserService implements UserServiceInterface{

	/**
	 *
	 */
	private $repository;
	/**
	 *
	 */
	private $userFactory;

	/**
	 * @param UserRepository $repository
	 * @param UserFactoryInterface $userFactory
	 */
	public function __construct(UserRepository $repository, UserFactoryInterface $userFactory) {
		$this->repository = $repository;
		$this->userFactory = $userFactory;
	}

	/**
	 * @param string $name
	 * @param string $password
	 * @param string|null $email
	 * @return \Logstats\Entities\User
	 */
	public function createUser($name, $password, $email = null) {
		$user = $this->userFactory->make(null, $name, bcrypt($password), $email);
		$this->repository->save($user);

		return $user;
	}

	/**
	 * @param User $user
	 * @param Role $role
	 * @return User
	 */
	public function addRoleToUser(User $user, Role $role) {
		$this->repository->addRoleToUser($user, $role);
	}
}