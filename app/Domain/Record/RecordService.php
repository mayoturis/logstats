<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Project\Project;
use Logstats\Support\Date\CarbonConvertorInterface;

class RecordService implements RecordServiceInterface{


	private $recordRepository;
	private $carbonConvertor;

	public function __construct(RecordRepository $recordRepository, CarbonConvertorInterface $carbonConvertor) {
		$this->recordRepository = $recordRepository;
		$this->carbonConvertor = $carbonConvertor;
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
		return $record;
	}

	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null) {
		return $this->recordRepository->getRecordsCountInInterval($project, $interval, $recordFilter);
	}
}