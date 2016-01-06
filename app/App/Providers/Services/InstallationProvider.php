<?php

namespace Logstats\App\Providers\Services;

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Services\Installation\InstallationService;
use Logstats\Domain\Services\Installation\InstallationServiceInterface;
use Logstats\Domain\Services\Installation\StepCollection;
use Logstats\Domain\Services\Installation\Steps;

class InstallationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		$this->registerStepCollection();
		$this->registerInstallationService();
	}

	private function registerStepCollection() {
		$this->app->singleton(StepCollection::class, function () {
			$steps = [1 => ['short' => Steps::WELCOME, 'menu' => 'Welcome',],
					  	2 => ['short' => Steps::DATABASE_SETUP, 'menu' => 'Database setup',],
					  	3 => ['short' => Steps::CREATE_TABLES, 'menu' => 'Create tables',],
					  	4 => ['short' => Steps::GENERAL_SETUP, 'menu' => 'General Setup',],
					  	5 => ['short' => Steps::CONGRATULATIONS, 'menu' => 'Congratulations',],
						'complete' => ['short' => Steps::COMPLETE, 'notShow' => true]


			];
			return new StepCollection($steps);
		});
	}

	private function registerInstallationService() {
		$this->app->bind(
			InstallationServiceInterface::class,
			InstallationService::class
		);
	}
}
