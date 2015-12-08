<?php

namespace Logstats\Http\Middleware;

use Closure;
use Logstats\Services\Data\CurrentProjectProviderInterface;

class ProjectChoosenMiddleware
{
	private $currentProjectProvider;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider) {
		$this->currentProjectProvider = $currentProjectProvider;
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
		if (!$this->currentProjectProvider->isSetProject()) {
			return redirect()->route('projects.index');
		}

        return $next($request);
    }
}
