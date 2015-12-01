<?php  namespace Logstats\Services\Entities; 

use Logstats\Entities\User;
use Logstats\ValueObjects\Role;

interface UserServiceInterface {

	/**
	 * @param string $name
	 * @param string $password
	 * @param string|null $email
	 * @return \Logstats\Entities\User
	 */
	public function createUser($name, $password, $email = null);

	/**
	 * @param User $user
	 * @param Role $role
	 * @return User
	 */
	public function addRoleToUser(User $user, Role $role);
}