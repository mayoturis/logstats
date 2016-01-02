<?php  namespace Logstats\Repositories; 

use Illuminate\Support\ServiceProvider;
use Logstats\Repositories\Contracts\RecordRepository;
use Logstats\Repositories\Database\DbRecordRepository;
use Logstats\Repositories\Database\DbProjectRepository;
use Logstats\Repositories\Database\DbUserRepository;
use Logstats\Services\Factories\UserFactory;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Repositories\Contracts\UserRepository;

class RepositoryProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerUserRepository();
		$this->registerProjectRepository();
		$this->registerRecordRepository();
	}

	private function registerUserRepository() {
		$this->app->bind(UserRepository::class, function() {
			$factory = new UserFactory();

			return new DbUserRepository($factory);
		});
	}

	private function registerProjectRepository() {
		$this->app->bind(
			ProjectRepository::class,
			DbProjectRepository::class
		);
	}

	private function registerRecordRepository() {
		$this->app->bind(
			RecordRepository::class,
			DbRecordRepository::class
		);
	}
}