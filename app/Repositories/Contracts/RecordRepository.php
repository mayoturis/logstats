<?php  namespace Logstats\Repositories\Contracts; 

use Logstats\Domain\Filters\MessageFilter;
use Logstats\Domain\Filters\RecordFilter;
use Logstats\Entities\Project;
use Logstats\Entities\Record;
use Logstats\ValueObjects\Pagination;

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
}