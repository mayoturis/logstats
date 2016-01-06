<?php

namespace Logstats\App\Providers\Services;

use Illuminate\Support\ServiceProvider;
use Logstats\Domain\Services\Database\ArtisanTableCreator;
use Logstats\Domain\Services\Database\DatabaseConfigService;
use Logstats\Domain\Services\Database\DatabaseConfigServiceInterface;
use Logstats\Domain\Services\Database\TableCreator;

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
