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
    }
}
