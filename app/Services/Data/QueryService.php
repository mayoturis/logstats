<?php  namespace Logstats\Services\Data; 

use Logstats\Domain\Query\Query;
use Logstats\Entities\Project;
use Logstats\Repositories\Contracts\RecordRepository;

class QueryService implements QueryServiceInterface{

	private $recordRepository;

	public function __construct(RecordRepository $recordRepository) {
		$this->recordRepository = $recordRepository;
	}

	/**
	 * @param Project $project
	 * @param Query $query
	 */
	public function getData(Project $project, Query $query) {
		return $this->recordRepository->getData($project, $query);
	}
}