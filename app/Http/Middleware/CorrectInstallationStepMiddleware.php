<?php

namespace Logstats\Http\Middleware;

use Closure;
use Logstats\Services\Installation\Steps;
use Mayoturis\Properties\RepositoryInterface;

class CorrectInstallationStepMiddleware
{

	/**
	 *
	 */
	private $config;

	public function __construct(RepositoryInterface $config) {
		$this->config = $config;
	}
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$step = $request->step;

		$expectedStep = $this->config->get('INSTALLATION_STEP');
		if (empty($expectedStep)) {
			$expectedStep = 1;
		}

		// if installation is complete
		if ($expectedStep == Steps::COMPLETE) {
			return redirect()->route('home');
		}

		if ($step != $expectedStep) {
			return redirect()->route('installation', ['step' => $expectedStep]);
		}

        return $next($request);
    }
}
