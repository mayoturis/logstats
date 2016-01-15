<?php  namespace Logstats\Infrastructure\Repositories\Database;

use Illuminate\Support\Facades\DB;
use Logstats\Domain\User\User;
use Logstats\Domain\User\UserRepository;
use Logstats\Infrastructure\Repositories\Database\Factories\StdUserFactory;

class DbUserRepository extends DbBaseRepository implements UserRepository{

	/**
	 * string name of the associated table
	 */
	private $table = 'users';

	private $roleTable = 'roles';

	/**
	 *  \Logstats\App\Entities\Factories\StdFactory
	 */
	private $userFactory;

	public function __construct(StdUserFactory $userFactory) {
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
		return $this->userFactory->makeFromStdArray($rawUsers);
	}

	/**
	 * @param int $id
	 * @return User
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

	/**
	 * @return User[]
	 */
	public function getAll() {
		return $this->findBy([]);
	}

	/**
	 * @param User $user
	 */
	public function delete(User $user) {
		DB::table($this->table)
			->where('id', $user->getId())
			->delete();
	}
}