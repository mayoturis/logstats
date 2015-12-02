<?php  namespace Logstats\Entities; 

use Carbon\Carbon;

class Record {

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var Carbon
	 */
	private $date;
	/**
	 * @var string
	 */
	private $level;
	/**
	 * @var int
	 */
	private $projectId;
	/**
	 * @var array
	 */
	private $context;

	/**
	 * @param string $level
	 * @param string $message
	 * @param Carbon $date
	 * @param int $projectId
	 * @param array $context
	 */
	public function __construct($level, $message, Carbon $date, $projectId, array $context) {
		$this->message = $message;
		$this->date = $date;
		$this->level = $level;
		$this->projectId = $projectId;
		$this->context = $context;
	}

	/**
	 * @return array
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @return int
	 */
	public function getProjectId() {
		return $this->projectId;
	}

	/**
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @return Carbon
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		if (!empty($this->id)) {
			throw new \BadMethodCallException('Id is already set');
		}
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
}