<?php

use Logstats\App\Installation\Database\Migration\DatabaseMigratorInterface;
use Logstats\App\Installation\Database\ProjectReadTokenCreator;

class ProjectReadTokenCreatorTest extends Testcase {
	public function test_create_read_tokens_calls_migrator() {
		$migrator = Mockery::mock(DatabaseMigratorInterface::class);
		$migrator->shouldReceive('migrate')->once();

		$prtc = new ProjectReadTokenCreator($migrator);
		$prtc->createReadTokens();
	}
}