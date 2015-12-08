<?php

use Logstats\Entities\Project;

Route::any('installation/{step}', ['as' => 'installation',
								   'middleware' => 'correct_installation_step',
								   'uses' => 'InstallationController@index']);


// accessible after installation
Route::group(['middleware' => 'installed'], function() {
	Route::get('auth/login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
	Route::post('auth/login', 'AuthController@postLogin');
	Route::get('auth/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
	Route::get('auth/register', 'UserController@create');
	Route::resource('user', 'UserController');

	// administration part
	Route::group(['middleware' => ['auth', 'can_visit']], function() {
		Route::get('/', ['as' => 'home', 'uses' => 'ProjectController@index']);
		Route::resource('projects', 'ProjectController');
		Route::get('how-to-send-logs', ['as' => 'how-to-send-logs', 'uses' => 'InfoController@howToSendLogs']);

		Route::group(['middleware' => 'project_choosen'], function() {
			Route::get('log', ['as' => 'log', 'uses' => 'LogController@index']);
		});
	});

	// both URLs should be able to handle data
	Route::post('', ['uses' => 'ArrivedDataController@dataArrived']);
	Route::post('api', ['uses' => 'ArrivedDataController@dataArrived']);

});
