<?php
use Logstats\Domain\Project\Project;

class ProjectTest extends TestCase{
	public function test_id_can_be_set_and_get_on_new_project() {
		$project = $this->getProject();
		$project->setId(5);

		$this->assertEquals(5, $project->getId());
	}

	public function test_setId_throws_exception_if_id_is_already_set() {
		$project = $this->getProject();
		$project->setId(5);

		try {
			$project->setId(6);
			$this->fail('Exception should be thrown');
		} catch(\BadMethodCallException $ex) {
			// ok
		}
	}

	public function test_name_can_be_set_and_get() {
		$project = $this->getProject();
		$project->setName('namo');

		$this->assertEquals('namo', $project->getName());
	}

	public function test_token_can_be_set_and_get() {
		$project = $this->getProject();
		$project->setToken('token');

		$this->assertEquals('token', $project->getToken());
	}

	public function test_createdAt_can_be_set_and_get_on_new_project() {
		$project = $this->getProject();
		$time = Mockery::mock('\Carbon\Carbon');
		$project->setCreatedAt($time);

		$this->assertEquals($time, $project->getCreatedAt());
	}

	public function test_createdAt_throws_exception_if_createdAt_is_already_set() {
		$project = $this->getProject();
		$time = Mockery::mock('\Carbon\Carbon');
		$time2 = Mockery::mock('\Carbon\Carbon');
		$project->setCreatedAt($time);

		try {
			$project->setCreatedAt($time2);
			$this->fail('Exception should be thrown');
		} catch(\BadMethodCallException $ex) {
			// ok
		}
	}

	private function getProject() {
		return new Project(null, null);
	}
}