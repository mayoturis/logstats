<?php  namespace Logstats\Domain\User;


interface UserRepository {

	/**
	 * Insert or update user
	 *
	 * @param User $user
	 * @return void
	 */
	public function save(User $user);

	/**
	 * Find users by condtitions
	 *
	 * @param array $conditions
	 * @return array of User
	 */
	public function findBy(array $conditions);

	/**
	 * Find first user by conditions
	 *
	 * @param array $conditions
	 * @return User
	 */
	public function findFirstBy(array $conditions);

	/**
	 * Gets user by id
	 *
	 * @param int $id
	 * @return User
	 */
	public function findById($id);

	/**
	 * Gets all users
	 *
	 * @return User[]
	 */
	public function getAll();

	/**
	 * Deletes user
	 *
	 * @param User $user
	 */
	public function delete(User $user);
}