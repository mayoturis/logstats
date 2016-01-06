<?php  namespace Logstats\App\Providers\User; 

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\User\UserRepository;
use Logstats\Domain\User\UserService;
use Logstats\Domain\User\UserServiceInterface;
use Logstats\Infrastructure\Repositories\Database\DbUserRepository;

class UserProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerUserService();
		$this->registerUserRepository();
	}

	private function registerUserService() {
		$this->app->bind(
			UserServiceInterface::class,
			UserService::class
		);
	}

	private function registerUserRepository() {
		$this->app->bind(
			UserRepository::class,
			DbUserRepository::class
		);
	}
}