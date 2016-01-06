<?php

namespace Logstats\App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Guard;

class CanVisitMiddleware
{

	private $auth;

	public function __construct(Guard $auth) {
		$this->auth = $auth;
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
		if ($this->auth->guest()) {
			return redirect()->route('login');
		}

		$user = $this->auth->user();

		if (!$user->isGeneralVisitor()) {
			throw new UnauthorizedException('Access Denied');
		}

        return $next($request);
    }
}
