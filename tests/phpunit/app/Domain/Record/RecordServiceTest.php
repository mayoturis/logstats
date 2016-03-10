<?php

use Illuminate\Contracts\Events\Dispatcher;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\RecordFilter;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Domain\Record\RecordService;
use Logstats\Support\Date\CarbonConvertorInterface;

class RecordServiceTest extends TestCase {
	public function test_createRecord() {
		$recordRepository = $this->getRecordRepository();
		$carbonConvertor = $this->getCarbonConvertor();
		$dispatcher = $this->getDispatcher();
		$carbon = Mockery::mock('Carbon\Carbon');
		$carbonConvertor->shouldReceive('carbonFromTimestampUTC')->with(5)->once()->andReturn($carbon);
		$recordService = new RecordService($recordRepository,$carbonConvertor,$dispatcher);
		$dispatcher->shouldReceive('fire')->once();
		$recordRepository->shouldReceive('newRecord')->once();
		$project = Mockery::mock(Project::class);
		$project->shouldReceive('getId')->once();
		$recordService->createRecord('level', 'message', 5, $project, []);
	}

	public function test_get_records_count_in_interval_calls_repository() {
		$recordRepository = $this->getRecordRepository();
		$carbonConvertor = $this->getCarbonConvertor();
		$dispatcher = $this->getDispatcher();
		$recordService = new RecordService($recordRepository,$carbonConvertor,$dispatcher);

		$project = $this->getProject();
		$interval = 'interval';
		$recordFilter = $this->getRecordFilter();
		$recordRepository->shouldReceive('getRecordsCountInInterval')->once()->with($project,$interval, $recordFilter);
		$recordService->getRecordsCountInInterval($project, $interval, $recordFilter);
	}

	public function test_deleteRecordsForProject_calls_repository() {
		$recordRepository = $this->getRecordRepository();
		$carbonConvertor = $this->getCarbonConvertor();
		$dispatcher = $this->getDispatcher();
		$recordService = new RecordService($recordRepository,$carbonConvertor,$dispatcher);

		$project = $this->getProject();
		$recordRepository->shouldReceive('deleteRecordsForProject')->once()->with($project);
		$recordService->deleteRecordsForProject($project);

	}

	/**
	 * @return \Mockery\MockInterface
	 */
	private function getRecordRepository() {
		$recordRepository = Mockery::mock(RecordRepository::class);
		return $recordRepository;
	}

	/**
	 * @return \Mockery\MockInterface
	 */
	private function getCarbonConvertor() {
		$carbonConvertor = Mockery::mock(CarbonConvertorInterface::class);
		return $carbonConvertor;
	}

	/**
	 * @return \Mockery\MockInterface
	 */
	private function getDispatcher() {
		$dispatcher = Mockery::mock(Dispatcher::class);
		return $dispatcher;
	}

	private function getProject() {
		return Mockery::mock(Project::class);
	}

	private function getRecordFilter() {
		return Mockery::mock(RecordFilter::class);
	}
}