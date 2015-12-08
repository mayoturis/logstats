<?php

namespace Logstats\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use Logstats\Http\Requests;
use Logstats\Http\Controllers\Controller;

class AuthController extends Controller
{

	private $auth;

	public function __construct(Guard $auth) {
		$this->auth = $auth;
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

		return redirect()->route('login');
	}
}
