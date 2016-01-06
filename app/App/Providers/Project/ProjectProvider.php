<?php  namespace Logstats\App\Providers\Project; 

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Project\ProjectService;
use Logstats\Domain\Project\ProjectServiceInterface;
use Logstats\Infrastructure\Repositories\Database\DbProjectRepository;

class ProjectProvider extends ServiceProvider{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->registerProjectService();
		$this->registerProjectRepository();
		$this->registerCurrentProjectProvider();
	}

	private function registerProjectService() {
		$this->app->bind(
			ProjectServiceInterface::class,
			ProjectService::class
		);
	}

	private function registerProjectRepository() {
		$this->app->bind(
			ProjectRepository::class,
			DbProjectRepository::class
		);
	}

	private function registerCurrentProjectProvider() {
		$this->app->bind(
			CurrentProjectProviderInterface::class,
			SessionCurrentProjectProvider::class
		);
	}
}