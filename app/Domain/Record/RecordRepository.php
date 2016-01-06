<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Project\Project;
use Logstats\Domain\ValueObjects\Pagination;

interface RecordRepository {

	/**
	 * @param Record $record
	 * @return void
	 */
	public function newRecord(Record $record);

	/**
	 * @param Project $project
	 * @param RecordFilter $conditions
	 * @return array of Record
	 */
	public function getRecordsByConditions(Project $project, RecordFilter $conditions = null, Pagination $pagination = null);

	/**
	 * @param Project $project
	 * @param RecordFilter $conditions
	 * @return int
	 */
	public function getRecordsCountByConditions(Project $project, RecordFilter $conditions = null);

	/**
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @return array of strings
	 */
	public function getMessagesByConditions(Project $project, MessageFilter $conditions = null, Pagination $pagination = null);

	/**
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @return int
	 */
	public function getMessagesCountByConditions(Project $project, MessageFilter $conditions = null);

	/**
	 * @param $messageId
	 * @return int
	 */
	public function getProjectIdForMessageId($messageId);

	/**
	 * @param int $messageId
	 * @return array of strings
	 */
	public function getPropertyNamesForMessageId($messageId);

	/**
	 * @param Project $project
	 * @param string $interval
	 * @param RecordFilter $recordFilter
	 */
	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null);
}