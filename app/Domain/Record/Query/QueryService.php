<?php  namespace Logstats\Domain\Record\Query;

use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\RecordRepository;

class QueryService implements QueryServiceInterface{

	private $recordRepository;

	/**
	 * @param RecordRepository $recordRepository
	 */
	public function __construct(RecordRepository $recordRepository) {
		$this->recordRepository = $recordRepository;
	}

	/**
	 * Gets data for Project by given Query
	 *
	 * @param Project $project
	 * @param Query $query
	 * @return array
	 */
	public function getData(Project $project, Query $query) {
		return $this->recordRepository->getData($project, $query);
	}
}