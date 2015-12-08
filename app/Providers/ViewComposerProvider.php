<?php

namespace Logstats\Providers;

use Illuminate\Support\ServiceProvider;
use Logstats\Http\ViewComposers\AuthUserComposer;
use Logstats\Http\ViewComposers\CurrentProjectComposer;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		view()->composer($this->adminViews(), AuthUserComposer::class);

		view()->composer($this->adminViews(), CurrentProjectComposer::class);
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

	private function adminViews() {
		return [
			'projects/*',
			'info/*',
			'log/*'
		];
	}
}
