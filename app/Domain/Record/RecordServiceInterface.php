<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Project\Project;

interface RecordServiceInterface {

	/**
	 * Creates new record
	 *
	 * @param string $level
	 * @param string $message
	 * @param int $timestamp timestamp
	 * @parem Project $project
	 * @param array $context
	 * @return Record
	 */
	public function createRecord($level, $message, $time, Project $project, array $context = []);

	/**
	 * Gets records count grouped in given interval
	 *
	 * @param Project $project
	 * @param string $interval
	 * @param RecordFilter $recordFilter
	 * @return array
	 */
	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null);

	/**
	 * Deletes all records for given project
	 *
	 * @param Project $project
	 */
	public function deleteRecordsForProject(Project $project);
}