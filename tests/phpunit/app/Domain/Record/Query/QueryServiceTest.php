<?php

use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\Query\Query;
use Logstats\Domain\Record\Query\QueryService;
use Logstats\Domain\Record\RecordRepository;

class QueryServiceTest extends TestCase {
	public function test_getData_calls_repository() {
		$recordRepository = $this->getRecordRepository();

		$queryService = new QueryService($recordRepository);
		$query = $this->getQuery();
		$data = 'someData';
		$project = $this->getProject();

		$recordRepository->shouldReceive('getData')->once()->with($project, $query)->andReturn($data);

		$this->assertEquals($data, $queryService->getData($project, $query));
	}

	private function getRecordRepository() {
		return Mockery::mock(RecordRepository::class);
	}

	private function getQuery() {
		return Mockery::mock(Query::class);
	}

	private function getProject() {
		return Mockery::mock(Project::class);
	}
}