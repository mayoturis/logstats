<?php  namespace Logstats\App\Providers\Services; 

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Services\Convertors\RecordsToCsvConvertor;
use Logstats\Domain\Services\Convertors\RecordsToCsvConvertorInterface;

class ConvertorsProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(
			RecordsToCsvConvertorInterface::class,
			RecordsToCsvConvertor::class
		);
	}
}