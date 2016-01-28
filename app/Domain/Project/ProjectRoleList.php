<?php  namespace Logstats\Domain\Project; 

use Logstats\Domain\User\Role;
use Logstats\Domain\User\RoleTypes;
use Logstats\Domain\User\User;

class ProjectRoleList {

	/**
	 * @var Role[]
	 */
	private $userRoles = [];
	/**
	 * @var User[]
	 */
	private $users = [];

	/**
	 * Sets (or creates new) role for user
	 *
	 * @param User $user
	 * @param Role $role
	 */
	public function setRole(User $user, Role $role) {
		if (!$this->userExists($user)) {
			$this->users[$user->getId()]  = $user;
		}

		$this->userRoles[$user->getId()] = $role;
	}

	private function userExists(User $user) {
		return array_key_exists($user->getId(), $this->users);
	}

	/**
	 * Determines whether user is admin in this ProjectRoleList
	 *
	 * @param User $user
	 * @return bool
	 */
	public function isAdmin(User $user) {
		return $this->isRole($user, RoleTypes::ADMIN);
	}

	/**
	 * Determines whether user is datamanger in this ProjectRoleList
	 *
	 * @param User $user
	 * @return bool
	 */
	public function isDataManager(User $user) {
		return $this->isRole($user, RoleTypes::DATAMANAGER);
	}

	/**
	 * Determines whether user is visitor in this ProjectRoleList
	 *
	 * @param User $user
	 * @return bool
	 */
	public function isVisitor(User $user) {
		return $this->isRole($user, RoleTypes::VISITOR);
	}

	private function isRole(User $user, $roleString) {
		if (!$this->userExists($user)) {
			return false;
		}

		$role = $this->userRoles[$user->getId()];

		return $role->isRole($roleString);
	}

	/**
	 * Gets all users for this ProjectRoleList
	 *
	 * @return User[]
	 */
	public function getUsers() {
		return $this->users;
	}

	/**
	 * Determines user role in this ProjectRoleList
	 *
	 * @param User $user
	 * @return Role|null
	 */
	public function getRoleForUser(User $user) {
		if (!$this->userExists($user)) {
			return null;
		}

		return $this->userRoles[$user->getId()];
	}
}