<?php  namespace Logstats\DTOs; 

use Carbon\Carbon;
use Logstats\Entities\Project;

class ProjectLastRecordDTO {

	private $project;
	private $lastRecordDate;

	public function __construct(Project $project, Carbon $lastRecordDate = null) {
		$this->project = $project;
		$this->lastRecordDate = $lastRecordDate;
	}

	/**
	 * @return Carbon
	 */
	public function getLastRecordDate() {
		return $this->lastRecordDate;
	}

	/**
	 * @return Project
	 */
	public function getProject() {
		return $this->project;
	}
}