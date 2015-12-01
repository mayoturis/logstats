<?php  namespace Logstats\Repositories; 

use Illuminate\Support\ServiceProvider;
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
}