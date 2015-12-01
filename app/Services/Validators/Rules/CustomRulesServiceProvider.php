<?php

namespace Logstats\Services\Validators\Rules;

use Illuminate\Support\ServiceProvider;

class CustomRulesServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('file_can_be_created', InstallationRules::class . '@fileCanBeCreated');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
