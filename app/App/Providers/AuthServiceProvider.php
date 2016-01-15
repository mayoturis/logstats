<?php

namespace Logstats\App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Logstats\App\Policies\UserPolicy;
use Logstats\Domain\Project\Project;
use Logstats\App\Policies\ProjectPolicy;
use Logstats\Domain\User\User;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		Project::class => ProjectPolicy::class,
		User::class => UserPolicy::class
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
		parent::registerPolicies($gate);
		$this->registerProjectPolicies($gate);
		$this->registerUserPolicies($gate);
    }

	public function registerProjectPolicies(GateContract $gate) {
		$gate->define('create.project', ProjectPolicy::class . '@create');
		$gate->define('store.project', ProjectPolicy::class . '@store');
		//$gate->define('show.records', RecordPolicy::class . '@show');
	}

	private function registerUserPolicies(GateContract $gate) {
		$gate->define('delete.user', UserPolicy::class . '@delete');
	}
}
