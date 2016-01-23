<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('installation/{step?}', ['as' => 'installation', function(Request $request, $step) {
	$mainStep = $step < 3 ? 1 : 2;
	$request =  request()->create("installation/$mainStep/$step", $request->method(), $request->all());
	return Route::dispatch($request);
}]);

Route::any('installation/1/{step}', ['middleware' => 'correct_installation_step',
								   'uses' => 'InstallationConfigurationController@index']);
Route::any('installation/2/{step}', ['middleware' => 'correct_installation_step',
								   'uses' => 'InstallationPostConfigurationController@index']);


// accessible after installation
Route::group(['middleware' => 'installed'], function() {
	// log in/log out
	Route::get('auth/login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
	Route::post('auth/login', 'AuthController@postLogin');
	Route::get('auth/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
	Route::get('auth/register', ['as' => 'register', 'uses' => 'UserController@create']);

	Route::resource('user', 'UserController', ['only' => ['create', 'store', 'destroy']]);

	// segmentation query
	Route::get('query', ['as' => 'query', 'uses' => 'QueryController@get']);

	// administration part
	Route::group(['middleware' => ['auth']], function() {
		Route::get('/', ['as' => 'home', 'uses' => 'ProjectController@index']);
		Route::resource('projects', 'ProjectController', ['only' => ['index', 'create', 'store', 'show', 'destroy']]);

		Route::get('how-to-send-logs', ['as' => 'how-to-send-logs', 'uses' => 'InfoController@howToSendLogs']);
		// User management
		Route::get('user-management', ['as' => 'user-management', 'middleware' => 'admin', 'uses' => 'UserManagementController@index']);
		Route::post('user-management-all', ['as' => 'user-management-all', 'middleware' => 'admin', 'uses' => 'UserManagementController@saveUsersRoles']);
		Route::post('user-management-project', ['as' => 'user-management-project', 'middleware' => 'admin', 'uses' => 'UserManagementController@saveProjectRoles']);

		Route::get('settings', ['as' => 'settings', 'middleware' => 'admin', 'uses' => 'SettingsController@index']);
		Route::post('settings-store', ['as' => 'settings-store', 'middleware' => 'admin', 'uses' => 'SettingsController@store']);

		// for log
		Route::get('record/ajax-show', ['as' => 'ajax-get-records', 'uses' => 'RecordController@ajaxShow']);

		// for segmentation
		Route::get('record/ajax-messages', ['as' => 'ajax-messages', 'uses' => 'RecordController@ajaxMessages']);
		Route::get('record/ajax-property-names', ['as' => 'ajax-property-names', 'uses' => 'RecordController@ajaxPropertyNames']);

		// accessible after project was choosen
		Route::group(['middleware' => 'project_choosen'], function() {
			Route::get('log', ['as' => 'log', 'uses' => 'LogController@index']);
			Route::get('export-csv', ['as' => 'export-csv', 'uses' => 'ExportController@csv']);

			Route::get('segmentation', ['as' => 'segmentation', 'uses' => 'SegmentationController@index']);

			Route::resource('alerting', 'AlertingController', ['only' => ['index', 'store', 'destroy']]);

			Route::get('project-management', ['as' => 'project-management', 'uses' => 'ProjectManagementController@index']);
			Route::delete('project-management/delete-records', ['as' => 'project-management.deleteRecords', 'uses' => 'ProjectManagementController@deleteRecords']);
		});
	});

	Route::post('api', ['uses' => 'IncomingDataController@store']);
});
