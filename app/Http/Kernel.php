<?php

namespace Logstats\Http;

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
        \Logstats\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Logstats\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Logstats\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \Logstats\Http\Middleware\RedirectIfAuthenticated::class,

		// Own middleware
		'installed' => \Logstats\Http\Middleware\InstalledMiddleware::class,
		'correct_installation_step' => \Logstats\Http\Middleware\CorrectInstallationStepMiddleware::class,
		'can_visit' => \Logstats\Http\Middleware\CanVisitMiddleware::class,
		'project_choosen' => \Logstats\Http\Middleware\ProjectChoosenMiddleware::class,
    ];
}
