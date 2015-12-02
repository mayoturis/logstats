<?php

namespace Logstats\Services\Entities;

use Illuminate\Support\ServiceProvider;
use Logstats\Services\Factories\UserFactory;
use Logstats\Repositories\Contracts\UserRepository;

class EntitiesProvider extends ServiceProvider
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
		$this->registerUserService();
		$this->registerProjectService();
		$this->registerRecordService();
	}

	private function registerUserService() {
		$this->app->bind(UserServiceInterface::class, function($app) {
			$repo = $app->make(UserRepository::class);

			return new UserService($repo, new UserFactory());
		});
	}

	private function registerProjectService() {
		$this->app->bind(
			ProjectServiceInterface::class,
			ProjectService::class
		);
	}

	private function registerRecordService() {
		$this->app->bind(
			RecordServiceInterface::class,
			RecordService::class
		);
	}
}
