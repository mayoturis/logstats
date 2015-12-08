<?php

use Logstats\Services\Entities\RecordService;

class RecordServiceTest extends TestCase {
	public function test_createRecord() {
		$dataRepository = Mockery::mock('Logstats\Repositories\Contracts\DataRepository');
		$recordService = new RecordService($dataRepository);
		$dataRepository->shouldReceive('newRecord')->once();
		$project = Mockery::mock('Logstats\Entities\Project');
		$project->shouldReceive('getId')->once();
		$recordService->createRecord('level', 'message', 5, $project, []);
	}
}