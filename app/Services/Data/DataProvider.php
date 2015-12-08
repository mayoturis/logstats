<?php  namespace Logstats\Services\Data; 

use Illuminate\Support\ServiceProvider;

class DataProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(
			DataServiceInterface::class,
			DataService::class
		);

		$this->app->bind(
			CurrentProjectProviderInterface::class,
			SessionCurrentProjectProvider::class
		);
	}
}