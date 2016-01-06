<?php

namespace Logstats\App\Http\Middleware;

use Closure;
use Mayoturis\Properties\RepositoryInterface;

class InstalledMiddleware
{

	private $config;

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function __construct(RepositoryInterface $config) {
		$this->config = $config;
	}

    public function handle($request, Closure $next)
    {
		$installationStep = $this->config->get('INSTALLATION_STEP');
		if (empty($installationStep)) {
			$installationStep = 1;
		}

		if ($installationStep != 'complete') {
			return redirect()->route('installation', ['step' => $installationStep]);
		}

        return $next($request);
    }
}
