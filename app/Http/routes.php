<?php

Route::any('installation/{step}', ['as' => 'installation',
								   'middleware' => 'correct_installation_step',
								   'uses' => 'InstallationController@index']);

// accessible after installation
Route::group(['middleware' => 'installed'], function() {
	// administration part
	Route::group(['middleware' => 'auth'], function() {

	});

	// both URLs should be able to handle data
	Route::get('', ['uses' => 'ArrivedDataController@dataArrived']);
	Route::get('api', ['uses' => 'ArrivedDataController@dataArrived']);

});

Route::get('s', function(\Logstats\Repositories\Contracts\ProjectRepository $repo, \Logstats\Services\Entities\ProjectService $serv, \Logstats\Repositories\Contracts\UserRepository $r) {
	$user = $r->findById(1);
	$serv->createProject('projectino', $user);
	dd($repo->findById(4));
});

