<?php

namespace Logstats\Services\Database;

use Illuminate\Support\ServiceProvider;
use Mayoturis\Properties\RepositoryInterface;

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

		$this->registerDatabaseCreator();
    }

	private function registerDatabaseCreator() {
		$this->app->bind(DatabaseCreator::class, function($app) {
			$envConfig = $app->make('\\Mayoturis\\Properties\\RepositoryInterface');
			$pdo = new \PDO("{$envConfig->get('DB_TYPE')}:user={$envConfig->get('DB_USERNAME')} password={$envConfig->get('DB_PASSWORD')}");

			return new PDODatabaseCreator($envConfig, $pdo);
		});
	}
}
