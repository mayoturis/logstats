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
	 * @param int $id
	 * @return \Logstats\Entities\User
	 */
	public function findById($id) {
		$rawUser = \DB::table($this->table)
			->where('id',$id)
			->first();
		$user = $this->userFactory->makeFromStd($rawUser);
		$user->setRoles($this->findRolesForUser($user));

		return $user;
	}


	/**
	 * Find role by its name
	 *
	 * @param string $name Name of the role
	 * @return \Logstats\ValueObjects\Role
	 */
	public function findRoleByName($name) {
		$rawRole = \DB::table($this->roleTable)->where('name', $name)->first();

		if (empty($rawRole)) {
			return null;
		}

		return new Role($rawRole->id, $rawRole->name);
	}

	/**
	 * Add new global role to user
	 *
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addRoleToUser(User $user, Role $role) {
		$count = \DB::table($this->roleUserTable)
			->where('user_id', $user->getId())
			->where('role', $role->getName())->count();

		if ($count > 0) { // association already exists
			return;
		}

		\DB::table($this->roleUserTable)->insert([
			'user_id' => $user->getId(),
			'role' => $role->getName()
		]);
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
			'remember_token' => $user->getRememberToken()
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
				'remember_token' => $user->getRememberToken()
			]);
	}

	/**
	 * @param User $user
	 * @return Collection of Role
	 */
	public function findRolesForUser(User $user) {
		$rawRoles = \DB::table($this->roleUserTable)->where('user_id', $user->getId())->get(['role']);

		$coll = new Collection();

		foreach ($rawRoles as $rowRole) {
			$coll->push(new Role($rowRole->role));
		}

		return $coll;
	}
}