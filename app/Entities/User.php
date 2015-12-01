<?php  namespace Logstats\Entities; 

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Logstats\ValueObjects\Role;

class User implements Authenticatable {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string|null
	 */
	private $remember_token;

	/**
	 * @var string|null
	 */
	private $email;
	/**
	 * @var Collection of Role
	 */
	private $roles = [];

	/**
	 * @param string $name
	 * @param string $password
	 * @param string $email
	 * @param string $remember_token
	 */
	public function __construct($name, $password, $email, $remember_token) {
		$this->name = $name;
		$this->password = $password;
		$this->remember_token = $remember_token;
		$this->email = $email;
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier() {
		return $this->getId();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword() {
		return $this->getPassword();
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken() {
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 * @return void
	 */
	public function setRememberToken($value) {
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName() {
		return 'remember_token';
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return null|string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param null|string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		if (!empty($this->id)) {
			throw new \BadMethodCallException('Id is already set and cannot be changed');
		}
		$this->id = $id;
	}

	/**
	 * @return Collection of Role
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param Collection of Role $roles
	 */
	public function setRoles(Collection $roles) {
		$this->roles = $roles;
	}
}