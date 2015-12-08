<?php  namespace Logstats\ValueObjects; 

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

	public function __toString() {
		return $this->name;
	}

	public function allSubRoles() {
		return RoleTypes::allSubRoles($this->name);
	}
}