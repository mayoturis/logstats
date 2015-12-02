<?php  namespace Logstats\Services\Entities; 

use Logstats\Entities\Project;
use Logstats\Entities\Record;

interface RecordServiceInterface {

	/**
	 * @param string $level
	 * @param string $message
	 * @param int $time timestamp
	 * @parem Project $project
	 * @param array $context
	 * @return Record
	 */
	public function createRecord($level, $message, $time, Project $project, array $context = []);
}