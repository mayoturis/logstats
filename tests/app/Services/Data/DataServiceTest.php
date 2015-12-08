<?php

use Logstats\Services\Data\DataService;
use Logstats\Services\Validators\ValidationException;

class DataServiceTest extends TestCase {
	public function test_newData_throws_exception_if_data_root_is_invalid() {
		list($pr, $incomingDataValidator, $rsi) = $this->getMocks();
		$dataService = new DataService($pr, $incomingDataValidator, $rsi);
		$data = ['someData'];
		$incomingDataValidator->shouldReceive('isValidRoot')->once()->with($data)->andReturn(false);
		$incomingDataValidator->shouldReceive('getErrors')->once()->andReturn(null);

		try {
			$dataService->newData($data);
			$this->fail('Exception shoud be thrown');
		} catch(ValidationException $ex) {
			// ok
		}
	}

	public function test_newData_should_call_validators_and_repositories_ntimes_when_more_messages() {
		list($projectRepository, $incomingDataValidator, $recordService) = $this->getMocks();
		$dataService = new DataService($projectRepository, $incomingDataValidator, $recordService);
		$project = Mockery::mock('Logstats\Entities\Project');
		$data = $this->getTwoMessageData();
		$incomingDataValidator->shouldReceive('isValidRoot')->once()->with($data)->andReturn(true);
		$projectRepository->shouldReceive('findByToken')->once()->with('projectToken')->andReturn($project);
		$incomingDataValidator->shouldReceive('isValidRecord')->twice()->andReturn(true);
		$recordService->shouldReceive('createRecord')->twice();
		$dataService->newData($data);
	}

	public function test_newData_should_call_validators_and_repositories_with_correct_data() {
		list($projectRepository, $incomingDataValidator, $recordService) = $this->getMocks();
		$dataService = new DataService($projectRepository, $incomingDataValidator, $recordService);
		$project = Mockery::mock('Logstats\Entities\Project');
		$data = $this->getOneMessageData();
		$incomingDataValidator->shouldReceive('isValidRoot')->once()->with($data)->andReturn(true);
		$projectRepository->shouldReceive('findByToken')->once()->with('projectToken')->andReturn($project);
		$incomingDataValidator->shouldReceive('isValidRecord')->once()->with($data['messages'][0])->andReturn(true);
		$recordService->shouldReceive('createRecord')->once()->with('level', 'message', 1, $project, ['context']);
		$dataService->newData($data);
	}

	public function test_newData_should_create_empty_context_if_one_is_not_set() {
		list($projectRepository, $incomingDataValidator, $recordService) = $this->getMocks();
		$dataService = new DataService($projectRepository, $incomingDataValidator, $recordService);
		$project = Mockery::mock('Logstats\Entities\Project');
		$data = $this->getOneMessageDataWithoutContext();
		$incomingDataValidator->shouldReceive('isValidRoot')->once()->with($data)->andReturn(true);
		$projectRepository->shouldReceive('findByToken')->once()->with('projectToken')->andReturn($project);
		$incomingDataValidator->shouldReceive('isValidRecord')->once()->with($data['messages'][0])->andReturn(true);
		$recordService->shouldReceive('createRecord')->once()->with('level', 'message', 1, $project, []);
		$dataService->newData($data);
	}


	private function getMocks() {
		return [
			Mockery::mock('Logstats\Repositories\Contracts\ProjectRepository'),
			Mockery::mock('Logstats\Services\Validators\IncomingDataValidator'),
			Mockery::mock('Logstats\Services\Entities\RecordServiceInterface'),
		];
	}

	private function getOneMessageData() {
		return [
			"project" => "projectToken",
			"messages" => [[
				'level' => 'level',
				'message' => 'message',
				'context' => ['context'],
				'time' => 1,
			]]
		];
	}

	private function getTwoMessageData() {
		return [
			"project" => "projectToken",
			"messages" => [
				[
					'level' => 'level1',
					'message' => 'message1',
					'context' => ['context'],
					'time' => 1,
				],
				[
					'level' => 'level2',
					'message' => 'message2',
					'context' => ['context'],
					'time' => 2,
				]
			]
		];
	}

	private function getOneMessageDataWithoutContext() {
		return [
			"project" => "projectToken",
			"messages" => [[
				'level' => 'level',
				'message' => 'message',
				'time' => 1,
			]]
		];
	}
}