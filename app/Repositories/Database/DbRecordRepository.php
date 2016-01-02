<?php  namespace Logstats\Repositories\Database;

use Logstats\Domain\Filters\MessageFilter;
use Logstats\Domain\Filters\RecordFilter;
use Logstats\Domain\Query\Query;
use Logstats\Entities\Project;
use Logstats\Entities\Record;
use Logstats\Repositories\Contracts\RecordRepository;
use Logstats\ValueObjects\Pagination;

class DbRecordRepository implements RecordRepository {

	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	/**
	 * @var DbRecordSaver
	 */
	private $recordSaver;

	/**
	 * @var DbRecordFinder
	 */
	private $recordFinder;
	/**
	 * @var DbMessageFinder
	 */
	private $messageFinder;
	/**
	 * @var DbPropertyFinder
	 */
	private $propertyFinder;
	/**
	 * @var DbByQueryFinder
	 */
	private $dbByQueryFinder;


	public function __construct(DbRecordSaver $recordSaver,
								DbRecordFinder $recordFinder,
								DbMessageFinder $messageFinder,
								DbPropertyFinder $propertyFinder,
								DbByQueryFinder $dbByQueryFinder) {
		$this->recordSaver = $recordSaver;
		$this->recordFinder = $recordFinder;
		$this->messageFinder = $messageFinder;
		$this->propertyFinder = $propertyFinder;
		$this->dbByQueryFinder = $dbByQueryFinder;
	}

	/**
	 * @param Record $record
	 * @return void
	 */
	public function newRecord(Record $record) {
		$this->recordSaver->newRecord($record);
	}

	/**
	 * @param RecordFilter $conditions
	 * @return array of Record
	 */
	public function getRecordsByConditions(Project $project, RecordFilter $conditions = null, Pagination $pagination = null) {
		return $this->recordFinder->getRecordsByConditions($project, $conditions, $pagination);
	}

	/**
	 * @param Project $project
	 * @param RecordFilter $conditions
	 * @return int
	 */
	public function getRecordsCountByConditions(Project $project, RecordFilter $conditions = null) {
		return $this->recordFinder->getRecordsCountByConditions($project, $conditions);
	}

	/**
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @return array of strings
	 */
	public function getMessagesByConditions(Project $project, MessageFilter $conditions = null, Pagination $pagination = null) {
		return $this->messageFinder->getMessagesByConditions($project, $conditions);
	}

	/**
	 * @param Project $project
	 * @param MessageConditions $conditions
	 * @return int
	 */
	public function getMessagesCountByConditions(Project $project, MessageFilter $conditions = null) {
		return $this->messageFinder->getMessagesCountByConditions($project, $conditions);
	}

	/**
	 * @param $messageId
	 * @return int
	 */
	public function getProjectIdForMessageId($messageId) {
		return $this->messageFinder->getProjectIdForMessageId($messageId);
	}

	/**
	 * @param int $messageId
	 * @return array of strings
	 */
	public function getPropertyNamesForMessageId($messageId) {
		return $this->propertyFinder->getPropertyNamesForMessageId($messageId);
	}

	public function getData(Project $project, Query $query) {
		return $this->dbByQueryFinder->getData($project, $query);
	}
}