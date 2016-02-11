<?php

namespace Logstats\App\Http\Middleware;

use Closure;
use Logstats\App\Installation\StepCollection;
use Mayoturis\Properties\RepositoryInterface;

class InstalledMiddleware
{

	private $config;
	private $installationSteps;

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function __construct(RepositoryInterface $config, StepCollection $installationSteps) {
		$this->config = $config;
		$this->installationSteps = $installationSteps;
	}

    public function handle($request, Closure $next)
    {
		$installationStep = $this->config->get('INSTALLATION_STEP');
		if (empty($installationStep)) {
			$installationStep = 1;
		}

		$completeInstallationStepKey = $this->installationSteps->getKeyByShort('complete');

		if ($installationStep != $completeInstallationStepKey) {
			return redirect()->route('installation', ['step' => $installationStep]);
		}

        return $next($request);
    }
}
