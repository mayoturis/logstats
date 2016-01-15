<?php

namespace Logstats\App\Providers;

use Illuminate\Support\ServiceProvider;
use Logstats\App\Http\ViewComposers\AuthUserComposer;
use Logstats\App\Http\ViewComposers\CurrentProjectComposer;
use Logstats\App\Http\ViewComposers\InstallationStepsViewComposer;
use Logstats\App\Http\ViewComposers\TimezoneViewComposer;

class ViewComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		view()->composer($this->adminViewFolders(), AuthUserComposer::class);

		view()->composer($this->adminViewFolders(), CurrentProjectComposer::class);

		view()->composer('*', TimezoneViewComposer::class);

		view()->composer('installation/*', InstallationStepsViewComposer::class);
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

	private function adminViewFolders() {
		return [
			'projects/*',
			'info/*',
			'log/*',
			'segmentation/*',
			'usermanagement/*',
			'user/*',
			'settings/*',
			'projectmanagement/*',
			'alerting/*',
		];
	}
}
