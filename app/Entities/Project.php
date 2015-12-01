<?php  namespace Logstats\Entities; 

use Carbon\Carbon;

class Project {

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
	private $token;

	/**
	 * @var \Carbon\Carbon
	 */
	private $createdAt;

	/**
	 * @param string $name
	 * @param string $token
	 */
	public function __construct($name, $token) {
		$this->name = $name;
		$this->token = $token;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 * @throws \BadMethodCallException
	 */
	public function setId($id) {
		if (!empty($this->id)) {
			throw new \BadMethodCallException('Id is already set and cannot be changed');
		}
		$this->id = $id;
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
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @param string $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * @return \Carbon\Carbon
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \Carbon\Carbon $createdAt
	 * @throws \BadMethodCallException
	 */
	public function  setCreatedAt(Carbon $createdAt) {
		if (!empty($this->createdAt)) {
			throw new \BadMethodCallException('createdAt is already set and cannot be changed');
		}
		$this->createdAt = $createdAt;
	}
}