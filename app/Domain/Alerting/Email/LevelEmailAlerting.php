<?php  namespace Logstats\Domain\Alerting\Email; 

use Logstats\Domain\Record\Record;

class LevelEmailAlerting {

	private $projectId;
	private $level;
	private $email;
	private $id;

	/**
	 * @param int $projectId
	 * @param int $level
	 * @param int $email
	 */
	public function __construct($projectId, $level, $email) {
		$this->projectId = $projectId;
		$this->level = $level;
		$this->email = $email;
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
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Returns true if current alerting matches record
	 *
	 * @param Record $record
	 * @return bool
	 */
	public function matchRecord(Record $record) {
		return $record->getLevel() == $this->level
		          && $record->getProjectId() == $this->projectId;
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
		$this->id = $id;
	}
}