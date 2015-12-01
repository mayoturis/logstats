<?php  namespace Logstats\Repositories\Contracts; 

use Logstats\Entities\User;
use Logstats\ValueObjects\Role;

interface UserRepository {

	/**
	 * Insert or update user
	 *
	 * @param User $user
	 * @return void
	 */
	public function save(User $user);

	/**
	 * @param int $id
	 * @return \Logstats\Entities\User
	 */
	public function findById($id);

	/**
	 * @param array $conditions
	 * @return \Illuminate\Support\Collection of \Logstats\Entities\User
	 */
	public function findBy(array $conditions);

	/**
	 * Find role by its name
	 *
	 * @param string $name Name of the role
	 * @return \Logstats\ValueObjects\Role
	 */
	public function findRoleByName($name);

	/**
	 * Add new global role to user
	 *
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addRoleToUser(User $user, Role $role);

	/**
	 * @param User $user
	 * @return \Illuminate\Support\Collection of Role
	 */
	public function findRolesForUser(User $user);
}