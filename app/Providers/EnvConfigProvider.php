<?php

namespace Logstats\Providers;

use Illuminate\Support\ServiceProvider;
use Mayoturis\Properties\RepositoryFactory;

class EnvConfigProvider extends ServiceProvider
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
        $this->app->singleton('\\Mayoturis\\Properties\\RepositoryInterface', function($app) {
			$envFilePath = base_path() . '/.env';

			return RepositoryFactory::make($envFilePath);
		});
    }
}
