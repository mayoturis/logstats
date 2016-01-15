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
	Route::get('auth/login', ['as' => 'login', 'uses' => 'AuthController@getLogin']);
	Route::post('auth/login', 'AuthController@postLogin');
	Route::get('auth/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);
	Route::get('auth/register', ['as' => 'register', 'uses' => 'UserController@create']);
	Route::resource('user', 'UserController');

	Route::get('query', ['as' => 'query', 'uses' => 'QueryController@get']);

	// administration part
	Route::group(['middleware' => ['auth']], function() {
		Route::get('/', ['as' => 'home', 'uses' => 'ProjectController@index']);
		Route::resource('projects', 'ProjectController');

		Route::get('how-to-send-logs', ['as' => 'how-to-send-logs', 'uses' => 'InfoController@howToSendLogs']);
		// User management
		Route::get('user-management', ['as' => 'user-management', 'middleware' => 'admin', 'uses' => 'UserManagementController@index']);
		Route::post('user-management-all', ['as' => 'user-management-all', 'middleware' => 'admin', 'uses' => 'UserManagementController@saveUsersRoles']);
		Route::post('user-management-project', ['as' => 'user-management-project', 'middleware' => 'admin', 'uses' => 'UserManagementController@saveProjectRoles']);

		Route::get('settings', ['as' => 'settings', 'middleware' => 'admin', 'uses' => 'SettingsController@index']);
		Route::post('settings-store', ['as' => 'settings-store', 'middleware' => 'admin', 'uses' => 'SettingsController@store']);

		Route::group(['middleware' => 'project_choosen'], function() {
			Route::get('log', ['as' => 'log', 'uses' => 'LogController@index']);
			Route::get('export-csv', ['as' => 'export-csv', 'uses' => 'ExportController@csv']);

			Route::get('segmentation', ['as' => 'segmentation', 'uses' => 'SegmentationController@index']);
			Route::get('record/ajax-show', ['as' => 'ajax-get-records', 'uses' => 'RecordController@ajaxShow']);
			Route::get('record/ajax-messages', ['as' => 'ajax-messages', 'uses' => 'RecordController@ajaxMessages']);
			Route::get('record/ajax-property-names', ['as' => 'ajax-property-names', 'uses' => 'RecordController@ajaxPropertyNames']);

			Route::resource('alerting', 'AlertingController');

			Route::get('project-management', ['as' => 'project-management', 'uses' => 'ProjectManagementController@index']);
			Route::delete('project-management/delete-records', ['as' => 'project-management.deleteRecords', 'uses' => 'ProjectManagementController@deleteRecords']);
		});

		Route::get('seed', function() {
			\Illuminate\Support\Facades\Artisan::call('db:seed');
		});
	});

	// both URLs should be able to handle data
	Route::post('', ['uses' => 'IncomingDataController@store']);
	Route::post('api', ['uses' => 'IncomingDataController@store']);
	/*Route::post('', ['uses' => 'RecordController@store']);
	Route::post('api', ['uses' => 'RecordController@store']);*/

});

Route::get('migrate', function() {
	include database_path() . '/migrations/' . '2014_10_12_100000_create_password_resets_table.php';
	include database_path() . '/migrations/' . '2015_11_13_134501_init_migration.php';
	include database_path() . '/migrations/' . '2015_11_21_222653_add_init_data.php';
	$m1 = new CreatePasswordResetsTable();
	$m2 = new InitMigration();
	$m3 = new AddInitData();
	$m1->up();
	$m2->up();
	$m3->up();
});

Route::get('seed', function() {
	include database_path() . '/seeds/ProjectWithRecordsSeeder.php';
	$seeder = new ProjectWithRecordsSeeder();
	$seeder->run();
});

Route::get('a', function(Request $request) {
	\Illuminate\Support\Facades\Mail::send('auth.login', [], function ($m)  {
		$m->from('hello@app.com', 'Your Application');

		$m->to('mtfotky@gmail.com', 'Marek Novák')->subject('Your Reminder!');
	});

	$mail = new PHPMailer(true);
	$mail->setFrom('from@example.com', 'Mailer');
	$mail->addAddress('mtfotky@gmail.com');               // Name is optional
	$mail->addReplyTo('info@example.com', 'Information');
	$mail->Subject = 'Here is the subject';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message has been sent';
	}
});