<?php

namespace Logstats\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Logstats\Domain\Project\Project;
use Logstats\Domain\User\User;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\User\RoleTypes;

class ProjectPolicy
{
    use HandlesAuthorization;

	private $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
		$this->projectRepository = $projectRepository;
	}

	public function create(User $user) {
		return $user->isGeneralDataManager();
	}

	public function store(User $user) {
		return $this->create($user);
	}

	public function delete(User $user, Project $project) {
		if ($user->isGeneralAdmin()) {
			return true;
		}

		return $this->hasRoleAtLeastInProject($user, $project, RoleTypes::ADMIN);
	}

	public function deleteRecords(User $user, Project $project) {
		return $this->delete($user, $project);
	}

	public function showRecords(User $user, Project $project) {
		return $this->show($user, $project);
	}

	public function showSegmentation(User $user, Project $project) {
		return $this->show($user, $project);
	}

	public function show(User $user, Project $project) {
		if ($user->isGeneralVisitor()) {
			return true;
		}

		return $this->hasRoleAtLeastInProject($user, $project, RoleTypes::VISITOR);
	}

	public function manageAlerting(User $user, Project $project) {
		if ($user->isGeneralAdmin()) {
			return true;
		}

		return $this->hasRoleAtLeastInProject($user, $project, RoleTypes::ADMIN);
	}

	private function hasRoleAtLeastInProject(User $user, Project $project, $role) {
		$userRole = $this->projectRepository->findRoleForUserInProject($user, $project);
		if ($userRole === null) {
			return false;
		}
		if (in_array($role, $userRole->allSubRoles())) {
			return true;
		}

		return false;
	}
}
