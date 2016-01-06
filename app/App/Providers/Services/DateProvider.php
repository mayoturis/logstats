<?php  namespace Logstats\App\Providers\Services;

use Illuminate\Support\ServiceProvider;
use Logstats\Support\Date\CarbonConvertor;
use Logstats\Support\Date\CarbonConvertorInterface;

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