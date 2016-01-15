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

	public function setRole(User $user, Role $role) {
		if (!$this->userExists($user)) {
			$this->users[$user->getId()]  = $user;
		}

		$this->userRoles[$user->getId()] = $role;
	}

	public function isAdmin(User $user) {
		return $this->isRole($user, RoleTypes::ADMIN);
	}

	public function isDataManager(User $user) {
		return $this->isRole($user, RoleTypes::DATAMANAGER);
	}

	public function isVisitor(User $user) {
		return $this->isRole($user, RoleTypes::VISITOR);
	}

	public function isRole(User $user, $roleString) {
		if (!$this->userExists($user)) {
			return false;
		}

		$role = $this->userRoles[$user->getId()];

		return $role->isRole($roleString);
	}

	private function userExists(User $user) {
		return array_key_exists($user->getId(), $this->users);
	}

	/**
	 * @return User[]
	 */
	public function getUsers() {
		return $this->users;
	}

	public function getRoleForUser(User $user) {
		if (!$this->userExists($user)) {
			return null;
		}

		return $this->userRoles[$user->getId()];
	}
}