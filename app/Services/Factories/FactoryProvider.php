<?php

namespace Logstats\Services\Factories;

use Illuminate\Support\ServiceProvider;

class FactoryProvider extends ServiceProvider
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
		$this->registerUserFactory();
        $this->registerProjectFactory();
		$this->registerRecordFactory();
    }

	private function registerUserFactory() {
		$this->app->bind(
			UserFactoryInterface::class,
			UserFactory::class
		);
	}

	private function registerProjectFactory() {
		$this->app->bind(
			ProjectFactoryInterface::class,
			ProjectFactory::class
		);
	}

	private function registerRecordFactory() {
		$this->app->bind(
			RecordFactoryInterface::class,
			RecordFactory::class
		);
	}
}
