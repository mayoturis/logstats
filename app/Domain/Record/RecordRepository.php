<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\Query\Intervals;
use Logstats\Domain\ValueObjects\Pagination;

interface RecordRepository {

	/**
	 * Inserts new record
	 *
	 * @param Record $record
	 */
	public function newRecord(Record $record);

	/**
	 * Gets record for project by given conditions
	 *
	 * @param Project $project
	 * @param RecordFilter $conditions
	 * @param Pagination $pagination Optional pagination
	 * @return Record[]
	 */
	public function getRecordsByConditions(Project $project, RecordFilter $conditions = null, Pagination $pagination = null);

	/**
	 * Gets count of the records for project by given conditions
	 *
	 * @param Project $project
	 * @param RecordFilter $conditions
	 * @return int
	 */
	public function getRecordsCountByConditions(Project $project, RecordFilter $conditions = null);

	/**
	 * Gets all messages for project by given conditions
	 *
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @param Pagination $pagination Optional pagination
	 * @return string[]
	 */
	public function getMessagesByConditions(Project $project, MessageFilter $conditions = null, Pagination $pagination = null);

	/**
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @param Gets count of messages for project by given conditions
	 * @return int
	 */
	public function getMessagesCountByConditions(Project $project, MessageFilter $conditions = null);

	/**
	 * Gets project id for given message id
	 *
	 * @param $messageId
	 * @return int
	 */
	public function getProjectIdForMessageId($messageId);

	/**
	 * Gets all property names associated with message id
	 *
	 * @param int $messageId
	 * @return string[]
	 */
	public function getPropertyNamesForMessageId($messageId);

	/**
	 * Gets records count grouped in given interval
	 *
	 * @param Project $project
	 * @param Intervals $interval
	 * @param RecordFilter $recordFilter
	 * @return array
	 */
	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null);

	/**
	 * Deletes all records for given project
	 *
	 * @param Project $project
	 * @return mixed
	 */
	public function deleteRecordsForProject(Project $project);
}