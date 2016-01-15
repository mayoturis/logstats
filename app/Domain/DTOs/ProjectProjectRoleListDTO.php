<?php  namespace Logstats\Domain\DTOs; 

use Logstats\Domain\Project\Project;
use Logstats\Domain\Project\ProjectRoleList;

class ProjectProjectRoleListDTO {

	private $project;
	private $projectRoleList;

	public function __construct(Project $project, ProjectRoleList $projectRoleList) {
		$this->project = $project;
		$this->projectRoleList = $projectRoleList;
	}

	/**
	 * @return Project
	 */
	public function getProject() {
		return $this->project;
	}

	/**
	 * @return ProjectRoleList
	 */
	public function getProjectRoleList() {
		return $this->projectRoleList;
	}
}