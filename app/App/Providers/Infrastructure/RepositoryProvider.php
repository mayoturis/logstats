<?php  namespace Logstats\App\Repositories; 

use Illuminate\Support\ServiceProvider;
use Logstats\App\Repositories\Contracts\RecordRepository;
use Logstats\App\Repositories\Database\DbRecordRepository;
use Logstats\App\Repositories\Database\DbProjectRepository;
use Logstats\App\Repositories\Database\DbUserRepository;
use Logstats\App\Services\Factories\UserFactory;
use Logstats\App\Repositories\Contracts\ProjectRepository;
use Logstats\App\Repositories\Contracts\UserRepository;

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



	private function registerRecordRepository() {
		$this->app->bind(
			RecordRepository::class,
			DbRecordRepository::class
		);
	}
}