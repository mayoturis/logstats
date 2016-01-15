<?php  namespace Logstats\Domain\Alerting\Email; 

use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\Record;

class LevelEmailAlerting {

	private $projectId;
	private $level;
	private $email;
	private $id;

	public function __construct($projectId, $level, $email) {
		$this->projectId = $projectId;
		$this->level = $level;
		$this->email = $email;
	}

	/**
	 * @return Project
	 */
	public function getProjectId() {
		return $this->projectId;
	}

	/**
	 * @return mixed
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	public function matchRecord(Record $record) {
		return $record->getLevel() == $this->level;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
}