<?php  namespace Logstats\Domain\Project;

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
	private $writeToken;

	/**
	 * @var string
	 */
	private $readToken;

	/**
	 * @var \Carbon\Carbon
	 */
	private $createdAt;

	/**
	 * @param string $name
	 * @param string $writeToken
	 */
	public function __construct($name, $writeToken, $readToken) {
		$this->name = $name;
		$this->writeToken = $writeToken;
		$this->readToken = $readToken;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @param int $id
	 * @throws \BadMethodCallException if id was already set
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
	public function getWriteToken() {
		return $this->writeToken;
	}

	/**
	 * @param string $writeToken
	 */
	public function setWriteToken($writeToken) {
		$this->writeToken = $writeToken;
	}

	/**
	 * @return \Carbon\Carbon
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @param \Carbon\Carbon $createdAt
	 * @throws \BadMethodCallException if created at was already set
	 */
	public function  setCreatedAt(Carbon $createdAt) {
		if (!empty($this->createdAt)) {
			throw new \BadMethodCallException('createdAt is already set and cannot be changed');
		}
		$this->createdAt = $createdAt;
	}

	/**
	 * @return string
	 */
	public function getReadToken() {
		return $this->readToken;
	}

	/**
	 * @param string $readToken
	 */
	public function setReadToken($readToken) {
		$this->readToken = $readToken;
	}
}