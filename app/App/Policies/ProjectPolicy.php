<?php

namespace Logstats\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Logstats\Domain\Project\Project;
use Logstats\Domain\User\User;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\App\ValueObjects\RoleTypes;

class ProjectPolicy
{
    use HandlesAuthorization;

	private $projectRepository;

	/**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        //
		$this->projectRepository = $projectRepository;
	}

	public function create(User $user) {
		return $user->isGeneralDataManager();
	}

	public function store(User $user) {
		return $this->create($user);
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

		$roles = $this->projectRepository->findRolesForUserInProject($user, $project);
		foreach ($roles as $role) {
			if (in_array(RoleTypes::VISITOR, $role->allSubRoles())) {
				return true;
			}
		}

		return false;
	}
	/*
	public function before(User $user, $ability) {
		if ($user->isGeneralAdmin()) {
			return true;
		}
	}*/
}
