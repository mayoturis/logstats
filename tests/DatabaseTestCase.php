<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\RecordServiceInterface;

class DatabaseTestCase extends TestCase {
	public function setUp() {
		parent::setUp();

		$this->createRows();
	}

	public function tearDown() {
		$this->emptyTables();

		parent::tearDown();
	}

	/*

	adminName (admin)
	datamanagerName (datamanager)
	visitor (visitor) - project1 = admin
	noneName (NO ROLE) - project = visitor


	 */
	private function createRows() {
		$this->emptyTables();
		$this->createUsers();
		$this->createProjects();
		$this->createProjectUserRoles();
		$this->createRecords();
		$this->createEmailAlertings();
	}

	private function createUsers() {
		DB::table('users')->insert([
			"id" => 1,
			"name" => "adminName",
			"password" => "adminPassword",
			"email" => "adminEmail",
			"role" => "admin"
		]);

		DB::table('users')->insert([
			"id" => 2,
			"name" => "datamanagerName",
			"password" => "datamanagerPassword",
			"email" => "datamanagerEmail",
			"role" => "datamanager",
			"remember_token" => "token",
		]);

		DB::table('users')->insert([
			"id" => 3,
			"name" => "visitorName",
			"password" => "visitorPassword",
			"email" => "visitorEmail",
			"role" => "visitor",
			"remember_token" => "token",
		]);

		DB::table('users')->insert([
			"id" => 4,
			"name" => "noneName",
			"password" => "nonePassword",
			"email" => "noneEmail",
			"role" => null
		]);
	}

	private function createProjects() {
		DB::table('projects')->insert([
			"id" => 1,
			'name' => 'project1',
			'write_token' => 'writeProject1Token',
			'read_token' => 'readProject1Token',
			'created_at' => Carbon::now()
		]);

		DB::table('projects')->insert([
			"id" => 2,
			'name' => 'project2',
			'write_token' => 'writeProject2Token',
			'read_token' => 'readProject2Token',
			'created_at' => Carbon::now()
		]);

		DB::table('projects')->insert([
			"id" => 3,
			'name' => 'query',
			'write_token' => 'writeQueryToken',
			'read_token' => 'readQueryToken',
			'created_at' => Carbon::now()
		]);
	}

	private function createProjectUserRoles() {
		DB::table('project_role_user')->insert([
			'user_id' => 3,
			'project_id' => 1,
			'role' => 'admin',
		]);

		DB::table('project_role_user')->insert([
			'user_id' => 4,
			'project_id' => 1,
			'role' => 'visitor',
		]);
	}

	//public function createRecord($level, $message, $timestamp, Project $project, array $context = []) {

	private function createRecords() {
		$recordService = $this->app->make(RecordServiceInterface::class);
		$project1 = new Project('','' ,'');
		$project1->setId(1);
		$project2 = new Project('','', '');
		$project2->setId(2);
		$queryProject = new Project('','', '');
		$queryProject->setId(3);
		$recordService->createRecord('info', 'message1', 500000, $project1);
		$recordService->createRecord('alert', 'message2', 500000, $project1);
		$recordService->createRecord('info', 'message1', 500000, $project1);
		$recordService->createRecord('info', 'message3', 500000, $project1, ['name' => 'name1', 'age' => 5, 'man' => true]);
		$recordService->createRecord('info', 'message3', 500000, $project1, ['name' => 'name2', 'age' => 10, 'man' => false]);
		$recordService->createRecord('debug', 'someOtherMessage', 500000, $project1);
		$recordService->createRecord('info', 'message3', 100000, $project2);

		$november = Carbon::createFromFormat('d.m.Y H:i', '10.11.2015 12:00')->getTimestamp();
		$december = Carbon::createFromFormat('d.m.Y H:i', '10.12.2015 12:00')->getTimestamp();

		$recordService->createRecord('info', 'purchase', $november, $queryProject, ['name' => 'marek', 'price' => 5]);
		$recordService->createRecord('info', 'purchase', $november, $queryProject, ['name' => 'marek', 'price' => 7]);
		$recordService->createRecord('info', 'purchase', $november, $queryProject, ['name' => 'john', 'price' => 9]);
		$recordService->createRecord('info', 'purchase', $november, $queryProject, ['name' => 'john', 'price' => 4]);
		$recordService->createRecord('info', 'purchase', $december, $queryProject, ['name' => 'marek', 'price' => 3]);
		$recordService->createRecord('info', 'purchase', $december, $queryProject, ['name' => 'marek', 'price' => 1]);
		$recordService->createRecord('info', 'purchase', $december, $queryProject, ['name' => 'john', 'price' => 2]);
		$recordService->createRecord('info', 'purchase', $december, $queryProject, ['name' => 'john', 'price' => 8]);
	}

	private function createEmailAlertings() {
		DB::table('email_send')->insert([
			'id' => 1,
			'email' => 'email1',
			'level' => 'info',
			'project_id' => 1
		]);

		DB::table('email_send')->insert([
			'id' => 2,
			'email' => 'email2',
			'level' => 'alert',
			'project_id' => 1
		]);

		DB::table('email_send')->insert([
			'id' => 3,
			'email' => 'email3',
			'level' => 'alert',
			'project_id' => 2
		]);
	}

	private function emptyTables() {
		DB::table('project_role_user')->delete();
		DB::table('users')->delete();
		DB::table('email_send')->delete();
		DB::table('property_types')->delete();
		DB::table('properties')->delete();
		DB::table('records')->delete();
		DB::table('messages')->delete();
		DB::table('projects')->delete();
	}
}