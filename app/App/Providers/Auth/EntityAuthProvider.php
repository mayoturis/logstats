<?php  namespace Logstats\App\Providers\Auth;

use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\ServiceProvider;
use Logstats\Domain\User\UserRepository;

class EntityAuthProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$userRepository = $this->app->make(UserRepository::class);

		$this->app['auth']->extend('entity',function() use($userRepository)
		{
			return new EntityUserProvider($userRepository, new BcryptHasher());
		});
	}
}