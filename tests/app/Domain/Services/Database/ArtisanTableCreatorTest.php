<?php

use Logstats\Domain\Services\Database\ArtisanTableCreator;

class ArtisanTableCreatorTest extends TestCase{

	public function test_MigrateDatabase_calls_command() {
		$kernel = IlluminateMocks::consoleKernel();
		$kernel->shouldReceive('call')->once()->with('migrate');

		$atc = new ArtisanTableCreator($kernel);
		$atc->migrateDatabase();
	}
}