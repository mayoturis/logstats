<?php  namespace Logstats\Repositories; 

use Illuminate\Support\Collection;
use Logstats\Services\Factories\StdFactory;
use Logstats\Services\Factories\UserFactoryInterface;
use Logstats\Entities\User;
use Logstats\Repositories\Contracts\UserRepository;
use Logstats\ValueObjects\Role;

class DbUserRepository extends DbBaseRepository implements UserRepository{

	/**
	 * string name of the associated table
	 */
	private $table = 'users';

	private $roleTable = 'roles';

	private $roleUserTable = 'role_user';
	/**
	 *  \Logstats\Entities\Factories\StdFactory
	 */
	private $userFactory;

	public function __construct(StdFactory $userFactory) {
		$this->userFactory = $userFactory;
	}

	public function save(User $user) {
		if ($user->getId() === null) {
			$this->insertUser($user);
		} else {
			$this->updateUser($user);
		}
	}

	/**
	 * Find user by condtitions
	 *
	 * @param array $conditions
	 * @return array of User
	 */
	public function findBy(array $conditions) {
		$rawUsers = $this->findRawBy($conditions);
		return $this->rawUsersToArrayOfUsers($rawUsers);
	}

	/**
	 * @param int $id
	 * @return \Logstats\Entities\User
	 */
	public function findById($id) {
		return $this->findFirstBy(['id' => $id]);
	}

	/**
	 * @return string Table name
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @return \Logstats\Services\Factories\StdFactory
	 */
	public function getStdFactory() {
		return $this->userFactory;
	}

	/**
	 * Insert user into database
	 *
	 * @param User $user
	 */
	private function insertUser(User $user) {
		$id = \DB::table($this->table)->insertGetId([
			'name' => $user->getName(),
			'email' => $user->getEmail(),
			'password' => $user->getPassword(),
			'remember_token' => $user->getRememberToken(),
			'role' => $user->getRole(),
		]);

		$user->setId($id);
	}

	/**
	 * Update user
	 *
	 * @param User $user
	 */
	private function updateUser(User $user) {
		\DB::table($this->table)->where('id', $user->getId())
			->update([
				'name' => $user->getName(),
				'email' => $user->getEmail(),
				'password' => $user->getPassword(),
				'remember_token' => $user->getRememberToken(),
				'role' => $user->getRole(),
			]);
	}

	private function rawUsersToArrayOfUsers(array $rawUsers) {
		$users = [];
		foreach ($rawUsers as $rawUser) {
			$users[] = $this->userFactory->makeFromStd($rawUsers);
		}

		return $users;
	}

	/**
	 * Find first user by conditions
	 *
	 * @param array $conditions
	 * @return User
	 */
	public function findFirstBy(array $conditions) {
		$rawUser = $this->findFirstRawBy($conditions);

		if (empty($rawUser)) {
			return null;
		}

		return $this->userFactory->makeFromStd($rawUser);
	}
}