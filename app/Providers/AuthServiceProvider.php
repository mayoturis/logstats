<?php

namespace Logstats\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Logstats\Entities\Project;
use Logstats\Policies\ProjectPolicy;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		Project::class => ProjectPolicy::class,
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
		$this->registerPolicies($gate);
    }

	public function registerPolicies(Gate $gate) {
		$gate->define('create.project', ProjectPolicy::class . '@create');
		$gate->define('create.store', ProjectPolicy::class . '@store');
	}
}
