<?php

use Logstats\Domain\Project\Project;
use Logstats\Domain\Project\ProjectRoleList;

class ProjectProjectRoleListDTOTest extends TestCase {
	public function test_dto_can_be_constructed_and_getters_work() {
		$project = $this->getProject();
		$projectRoleList = $this->getProjectRoleList();
		$dto = new \Logstats\Domain\DTOs\ProjectProjectRoleListDTO($project, $projectRoleList);

		$this->assertEquals($project, $dto->getProject());
		$this->assertEquals($projectRoleList, $dto->getProjectRoleList());
	}

	private function getProject() {
		return Mockery::mock(Project::class);
	}

	private function getProjectRoleList() {
		return Mockery::mock(ProjectRoleList::class);
	}
}