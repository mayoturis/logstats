<?php

namespace Logstats\App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Logstats\App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Logstats\App\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Logstats\App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \Logstats\App\Http\Middleware\RedirectIfAuthenticated::class,

		// Own middleware
		'installed' => \Logstats\App\Http\Middleware\InstalledMiddleware::class,
		'correct_installation_step' => \Logstats\App\Http\Middleware\CorrectInstallationStepMiddleware::class,
		'can_visit' => \Logstats\App\Http\Middleware\CanVisitMiddleware::class,
		'project_choosen' => \Logstats\App\Http\Middleware\ProjectChoosenMiddleware::class,
    ];
}
