<?php

namespace Logstats\App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Logstats\App\Http\Requests;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;

class AuthController extends Controller
{

	private $auth;
	private $currentProjectProvider;

	public function __construct(Guard $auth,
								CurrentProjectProviderInterface $currentProjectProvider) {
		$this->auth = $auth;
		$this->currentProjectProvider = $currentProjectProvider;
	}

	public function getLogin() {
		return view('auth.login');
	}

	public function postLogin(Request $request) {
		$remember = !empty($request->get('remember_me'));
		if ($this->auth->attempt(['name' => $request->get('name'), 'password' => $request->get('password')], $remember)) {
			return redirect()->intended(route('home'));
		}

		return redirect()->route('login')->withInput()->withErrors(['Invalid credentials'], 'login');
	}

	public function logout() {
		$this->auth->logout();
		$this->currentProjectProvider->unsetProject();

		return redirect()->route('login');
	}
}
