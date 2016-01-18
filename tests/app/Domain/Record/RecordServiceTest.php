<?php

use Illuminate\Contracts\Events\Dispatcher;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Domain\Record\RecordService;
use Logstats\Support\Date\CarbonConvertorInterface;

class RecordServiceTest extends TestCase {
	public function test_createRecord() {
		$recordRepository = Mockery::mock(RecordRepository::class);
		$carbonConvertor = Mockery::mock(CarbonConvertorInterface::class);
		$dispatcher = Mockery::mock(Dispatcher::class);
		$carbon = Mockery::mock('Carbon\Carbon');
		$carbonConvertor->shouldReceive('carbonFromTimestampUTC')->with(5)->once()->andReturn($carbon);
		$recordService = new RecordService($recordRepository,$carbonConvertor,$dispatcher);
		$dispatcher->shouldReceive('fire')->once();
		$recordRepository->shouldReceive('newRecord')->once();
		$project = Mockery::mock(Project::class);
		$project->shouldReceive('getId')->once();
		$recordService->createRecord('level', 'message', 5, $project, []);
	}
}