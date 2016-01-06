<?php

namespace Logstats\App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Logstats\Domain\Project\Project;
use Logstats\App\Policies\ProjectPolicy;
use Logstats\App\Policies\RecordPolicy;

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
		$this->registerAllPolicies($gate);
    }

	public function registerAllPolicies(GateContract $gate) {
		/*$gate->define('showrecords', function($user, $post) {
			return true;
		});*/
		$gate->define('create.project', ProjectPolicy::class . '@create');
		$gate->define('store.project', ProjectPolicy::class . '@store');
		//$gate->define('show.records', RecordPolicy::class . '@show');
	}
}
