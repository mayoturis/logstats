<?php  namespace Logstats\Services\Date; 

use Illuminate\Support\ServiceProvider;

class DateProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(
			CarbonConvertorInterface::class,
			CarbonConvertor::class
		);
	}
}