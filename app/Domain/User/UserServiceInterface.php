<?php  namespace Logstats\Domain\User;


interface UserServiceInterface {

	/**
	 * Creates new user
	 *
	 * @param string $name
	 * @param string $password
	 * @param string|null $email
	 * @return User
	 */
	public function createUser($name, $password, $email = null);

	/**
	 * Sets user a new role
	 *
	 * @param User $user
	 * @param Role $role
	 * @return User
	 */
	public function setUserRole(User $user, Role $role);

	/**
	 * Deletes user
	 *
	 * @param User $user
	 */
	public function delete(User $user);
}