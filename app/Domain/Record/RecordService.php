<?php  namespace Logstats\Domain\Record;

use Illuminate\Contracts\Events\Dispatcher;
use Logstats\Domain\Events\NewRecord;
use Logstats\Domain\Project\Project;
use Logstats\Support\Date\CarbonConvertorInterface;

class RecordService implements RecordServiceInterface {


	private $recordRepository;
	private $carbonConvertor;
	private $eventDispatcher;

	public function __construct(RecordRepository $recordRepository,
								CarbonConvertorInterface $carbonConvertor,
								Dispatcher $eventDispatcher) {
		$this->recordRepository = $recordRepository;
		$this->carbonConvertor = $carbonConvertor;
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	 * @param string $level
	 * @param string $message
	 * @param int $timestamp timestamp
	 * @parem Project $project
	 * @param array $context
	 * @return Record
	 */
	public function createRecord($level, $message, $timestamp, Project $project, array $context = []) {
		$record = new Record($level, $message, $this->carbonConvertor->carbonFromTimestampUTC($timestamp), $project->getId(), $context);
		$this->recordRepository->newRecord($record);
		$this->eventDispatcher->fire(new NewRecord($record));
		return $record;
	}

	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null) {
		return $this->recordRepository->getRecordsCountInInterval($project, $interval, $recordFilter);
	}

	/**
	 * @param Project $project
	 */
	public function deleteRecordsForProject(Project $project) {
		$this->recordRepository->deleteRecordsForProject($project);
	}
}