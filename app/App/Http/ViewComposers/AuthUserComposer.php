<?php  namespace Logstats\App\Http\ViewComposers; 

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;

class AuthUserComposer {

	private $auth;

	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	public function compose(View $view)
	{
		$view->with('user', $this->auth->user());
	}
}