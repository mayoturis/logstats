<?php

use Logstats\App\Installation\InstallationService;
use Logstats\App\Installation\StepCollection;

class InstallationServiceTest extends TestCase{

	public function test_setRandomAppKey_sets_random_key() {
		list($laravelConfig,$envConfig,$str,$steps) = $this->getMocks();
		$installationService = new InstallationService($laravelConfig, $envConfig, $str, $steps);
		$laravelConfig->shouldReceive('get')->once()->with('app.cipher');
		$str->shouldReceive('random')->once()->andReturn('random_key');
		$envConfig->shouldReceive('set')->once()->with('APP_KEY', 'random_key');

		$installationService->setRandomAppKey();
	}

	public function test_setInstallationStep() {
		list($laravelConfig,$envConfig,$str,$steps) = $this->getMocks();
		$installationService = new InstallationService($laravelConfig, $envConfig, $str, $steps);
		$step = 'step';
		$key = 'someKey';
		$steps->shouldReceive('getKeyByShort')->once()->with($step)->andReturn($key);
		$envConfig->shouldReceive('set')->once()->with('INSTALLATION_STEP', $key);
		$installationService->setInstallationStep($step);
	}

	public function test_setNextInstallationStep() {
		list($laravelConfig,$envConfig,$str,$steps) = $this->getMocks();
		$installationService = $this->getMock(InstallationService::class,
									['setInstallationStep'], [$laravelConfig,$envConfig,$str,$steps]);
		$step = 'someStep';
		$steps->shouldReceive('nextStepForShort')->once()->with($step)->andReturn(['short' => 'someShort']);
		$installationService->expects($this->once())->method('setInstallationStep')->with('someShort');
		$installationService->setNextInstallationStep($step);
	}

	private function getMocks() {
		return [
			Mockery::mock('Illuminate\Contracts\Config\Repository'),
			Mockery::mock('Mayoturis\Properties\RepositoryInterface'),
			Mockery::mock('Illuminate\Support\Str'),
			Mockery::mock(StepCollection::class),
		];
	}
}