<?php  namespace Logstats\Domain\User;

class Role {

	/**
	 * @var Role name
	 */
	private $name;

	/**
	 * @param string $name Role name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param $role
	 * @return bool
	 */
	public function isRole($role) {
		return in_array($role, $this->allSubRoles());
	}

	public function __toString() {
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function allSubRoles() {
		return RoleTypes::allSubRoles($this->name);
	}
}