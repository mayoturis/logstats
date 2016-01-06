<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Project\Project;

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

	/**
	 * @param Project $project
	 * @param string $interval
	 * @param RecordFilter $recordFilter
	 */
	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null);
}