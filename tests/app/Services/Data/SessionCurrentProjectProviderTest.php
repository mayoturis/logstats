<?php

use Logstats\Services\Data\SessionCurrentProjectProvider;

class SessionCurrentProjectProviderTest extends TestCase{

	public function test_current_project_can_be_set() {
		list($repository, $session, $project) = $this->getMocks();
		$scpp = new SessionCurrentProjectProvider($repository,$session);
		$id = 5;
		$project->shouldReceive('getId')->once()->andReturn($id);
		$session->shouldReceive('set')->with('current_project_id', $id);
		$scpp->set($project);
	}

	public function test_if_session_is_not_set_null_is_get() {
		list($repository, $session, $project) = $this->getMocks();
		$scpp = new SessionCurrentProjectProvider($repository,$session);
		$session->shouldReceive('get')->once()->with('current_project_id')->andReturn(null);

		$this->assertNull($scpp->get());
	}

	public function test_for_first_time_in_get_project_repository_is_called() {
		list($repository, $session, $project) = $this->getMocks();
		$scpp = new SessionCurrentProjectProvider($repository,$session);
		$id = 5;
		$session->shouldReceive('get')->once()->with('current_project_id')->andReturn($id);
		$repository->shouldReceive('findById')->once()->with($id)->andReturn($project);

		$this->assertEquals($project, $scpp->get());
	}

	public function test_session_get_and_repository_is_called_only_once() {
		list($repository, $session, $project) = $this->getMocks();
		$scpp = new SessionCurrentProjectProvider($repository,$session);
		$id = 5;
		$session->shouldReceive('get')->once()->with('current_project_id')->andReturn($id);
		$repository->shouldReceive('findById')->once()->with($id)->andReturn($project);

		$this->assertEquals($project, $scpp->get());
		$this->assertEquals($project, $scpp->get());
		$this->assertEquals($project, $scpp->get());
		$this->assertEquals($project, $scpp->get());
	}

	private function getMocks() {
		return [
			Mockery::mock('Logstats\Repositories\Contracts\ProjectRepository'),
			Mockery::mock('Symfony\Component\HttpFoundation\Session\SessionInterface'),
			Mockery::mock('Logstats\Entities\Project'),
		];
	}
}