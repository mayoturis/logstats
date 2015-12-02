<?php  namespace Logstats\Services\Entities; 

use Carbon\Carbon;
use Logstats\Entities\Project;
use Logstats\Entities\Record;
use Logstats\Repositories\Contracts\DataRepository;

class RecordService implements RecordServiceInterface{


	private $dataRepository;

	public function __construct(DataRepository $dataRepository) {
		$this->dataRepository = $dataRepository;
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
		$record = new Record($level, $message, $this->carbonFromTimestampUTC($timestamp), $project->getId(), $context);
		$this->dataRepository->newRecord($record);
		return $record;
	}

	private function carbonFromTimestampUTC($timestamp) {
		$timezone = Carbon::now()->getTimezone();
		$carbon = Carbon::createFromTimestampUTC($timestamp);
		$carbon->setTimezone($timezone);
		return $carbon;
	}
}