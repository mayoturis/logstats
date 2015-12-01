<?php

use Logstats\Services\Factories\ProjectFactory;

class ProjectFactoryTest extends TestCase {
	public function test_make_creates_project() {
		$projectFactory = new ProjectFactory();
		$project = $projectFactory->make(1, 'name', 'token');

		$this->assertEquals(1, $project->getId());
		$this->assertEquals('name', $project->getName());
		$this->assertEquals('token', $project->getToken());
	}
}