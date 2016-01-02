<?php

use Logstats\Services\Entities\RecordService;

class RecordServiceTest extends TestCase {
	public function test_createRecord() {
		$dataRepository = Mockery::mock('Logstats\Repositories\Contracts\RecordRepository');
		$carbonConvertor = Mockery::mock('Logstats\Services\Date\CarbonConvertorInterface');
		$carbon = Mockery::mock('Carbon\Carbon');
		$carbonConvertor->shouldReceive('carbonFromTimestampUTC')->with(5)->once()->andReturn($carbon);
		$recordService = new RecordService($dataRepository,$carbonConvertor);
		$dataRepository->shouldReceive('newRecord')->once();
		$project = Mockery::mock('Logstats\Entities\Project');
		$project->shouldReceive('getId')->once();
		$recordService->createRecord('level', 'message', 5, $project, []);
	}
}