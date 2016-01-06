<?php  namespace Logstats\App\Providers\Record; 

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Domain\Record\RecordService;
use Logstats\Domain\Record\RecordServiceInterface;
use Logstats\Infrastructure\Repositories\Database\DbRecordRepository;

class RecordProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerRecordService();
		$this->registerRecordRepository();
	}

	private function registerRecordService() {
		$this->app->bind(
			RecordServiceInterface::class,
			RecordService::class
		);
	}

	private function registerRecordRepository() {
		$this->app->bind(
			RecordRepository::class,
			DbRecordRepository::class
		);
	}
}