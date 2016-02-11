<?php

namespace Logstats\App\Http\Middleware;

use Closure;
use Logstats\App\Installation\StepCollection;
use Mayoturis\Properties\RepositoryInterface;

class CorrectInstallationStepMiddleware
{

	private $config;
	private $installationSteps;

	public function __construct(RepositoryInterface $config, StepCollection $installationSteps) {
		$this->config = $config;
		$this->installationSteps = $installationSteps;
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
		$completeInstallationStepKey = $this->installationSteps->getKeyByShort('complete');
		if ($expectedStep == $completeInstallationStepKey) {
			return redirect()->route('home');
		}

		if ($step != $expectedStep) {
			return redirect()->route('installation', ['step' => $expectedStep]);
		}

        return $next($request);
    }
}
