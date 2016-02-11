<?php

namespace Logstats\App\Providers\Services;

use Illuminate\Support\ServiceProvider;
use Logstats\App\Installation\Database\ArtisanTableCreator;
use Logstats\App\Installation\Database\DatabaseConfigService;
use Logstats\App\Installation\Database\DatabaseConfigServiceInterface;
use Logstats\App\Installation\Database\Migration\DatabaseMigrator;
use Logstats\App\Installation\Database\Migration\DatabaseMigratorInterface;
use Logstats\App\Installation\Database\ProjectReadTokenCreator;
use Logstats\App\Installation\Database\ProjectReadTokenCreatorInterface;
use Logstats\App\Installation\Database\TableCreator;

class DatabaseProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
		$this->app->bind(
			DatabaseConfigServiceInterface::class,
			DatabaseConfigService::class
		);

		$this->app->bind(
			TableCreator::class,
			ArtisanTableCreator::class
		);

		$this->app->bind(
			DatabaseMigratorInterface::class,
			DatabaseMigrator::class
		);

		$this->app->bind(
			ProjectReadTokenCreatorInterface::class,
			ProjectReadTokenCreator::class
		);
    }
}
