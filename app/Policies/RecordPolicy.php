<?php

namespace Logstats\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Logstats\Repositories\Contracts\ProjectRepository;

class RecordPolicy
{
    use HandlesAuthorization;

	private $projectRepository;

	/**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
		Log::info('kurva');
	}

	public function show(User $user, Project $project) {
		return true;
//		$roles = $this->projectRepository->findRolesForUserInProject($user, $project);
		dd($roles);
	}
}
