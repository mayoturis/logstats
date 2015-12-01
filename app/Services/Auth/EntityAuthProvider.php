<?php  namespace Logstats\Services\Auth; 

use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\ServiceProvider;

class EntityAuthProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$userRepository = $this->app->make('Logstats\Repositories\Contracts\UserRepository');

		$this->app['auth']->extend('entity',function() use($userRepository)
		{
			return new EntityUserProvider($userRepository, new BcryptHasher());
		});
	}
}