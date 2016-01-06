<?php  namespace Logstats\App\Providers\Record;

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Record\Query\QueryService;
use Logstats\Domain\Record\Query\QueryServiceInterface;


class QueryProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerQueryService();
	}

	private function registerQueryService() {
		$this->app->bind(
			QueryServiceInterface::class,
			QueryService::class
		);
	}
}