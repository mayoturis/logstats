<?php

namespace Logstats\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Logstats\Entities\Project;
use Logstats\Entities\User;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	public function create(User $user) {
		return $user->isGeneralDataManager();
	}

	public function store(User $user) {
		return $this->create($user);
	}

	public function before(User $user, $ability) {
		if ($user->isGeneralAdmin()) {
			return true;
		}
	}
}
