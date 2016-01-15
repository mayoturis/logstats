<?php  namespace Logstats\Domain\User;


interface UserServiceInterface {

	/**
	 * @param string $name
	 * @param string $password
	 * @param string|null $email
	 * @return User
	 */
	public function createUser($name, $password, $email = null);

	/**
	 * @param User $user
	 * @param Role $role
	 * @return User
	 */
	public function setUserRole(User $user, Role $role);

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function delete(User $user);
}