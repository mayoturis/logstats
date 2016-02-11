<?php

use Logstats\App\Installation\Database\ArtisanTableCreator;
use Logstats\App\Installation\Database\Migration\DatabaseMigratorInterface;

class ArtisanTableCreatorTest extends TestCase{

	public function test_MigrateDatabase_calls_migrator() {
		$migrator = Mockery::mock(DatabaseMigratorInterface::class);
		$migrator->shouldReceive('migrate')->once();

		$atc = new ArtisanTableCreator($migrator);
		$atc->migrateDatabase();
	}
}