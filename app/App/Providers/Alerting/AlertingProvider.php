<?php  namespace Logstats\App\Providers\Alerting; 


use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Alerting\Email\EmailAlerter;
use Logstats\Domain\Alerting\Email\EmailAlerterInterface;
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Infrastructure\Repositories\Database\DbLevelEmailAlertingRepository;

class AlertingProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind(
			LevelEmailAlertingRepository::class,
			DbLevelEmailAlertingRepository::class
		);

		$this->app->bind(
			EmailAlerterInterface::class,
			EmailAlerter::class
		);
	}
}