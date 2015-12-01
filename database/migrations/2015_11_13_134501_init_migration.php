<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('token')->unique();
			$table->timestamp('created_at');
		});

		Schema::create("roles", function(Blueprint $table) {
			$table->string('name');
			$table->primary('name');
		});

		Schema::create('project_role_user', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->unsignedInteger('project_id');
			$table->string('role');
			$table->foreign('user_id', 'project_role_user_user_id' . $this->generateRandomSuffix())->references('id')->on('users');
			$table->foreign('project_id', 'project_role_user_project_id' . $this->generateRandomSuffix())->references('id')->on('projects');
			$table->foreign('role', 'project_role_user_role' . $this->generateRandomSuffix())->references('name')->on('roles');
		});

		Schema::create('role_user', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('user_id');
			$table->string('role');
			$table->foreign('user_id', 'role_user_user_id'. $this->generateRandomSuffix())->references('id')->on('users');
			$table->foreign('role', 'role_user_role'. $this->generateRandomSuffix())->references('name')->on('roles');
		});

		Schema::create('messages', function(Blueprint $table) {
			$table->increments('id');
			$table->text('message');
			$table->unsignedInteger('project_id');
			$table->foreign('project_id', 'messages_project_id' . $this->generateRandomSuffix())->references('id')->on('projects');
		});

		Schema::create('property_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('property_name');
			$table->unsignedInteger('message_id');
			$table->foreign('message_id', 'property_types_message_id' . $this->generateRandomSuffix())->references('id')->on('messages');
		});

		Schema::create('levels', function(Blueprint $table) {
			$table->string('name');
			$table->primary('name');
		});

		Schema::create('records', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamp('date');
			$table->tinyInteger('minute');
			$table->tinyInteger('hour');
			$table->tinyInteger('day');
			$table->tinyInteger('month');
			$table->smallInteger('year');
			$table->unsignedInteger('project_id');
			$table->string('level');
			$table->unsignedInteger('message_id');
			$table->foreign('project_id', 'records_project_id'. $this->generateRandomSuffix())->references('id')->on('projects');
			$table->foreign('level', 'records_level'. $this->generateRandomSuffix())->references('name')->on('levels');
			$table->foreign('message_id', 'records_message_id'. $this->generateRandomSuffix())->references('id')->on('messages');
		});

		Schema::create('emails', function(Blueprint $table) {
			$table->increments('id');
			$table->string('email');
		});

		Schema::create('email_send', function(Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('project_id');
			$table->string('level');
			$table->unsignedInteger('email_id');
			$table->foreign('project_id', 'email_send_project_id' . $this->generateRandomSuffix())->references('id')->on('projects');
			$table->foreign('level', 'email_send_level' . $this->generateRandomSuffix())->references('name')->on('levels');
			$table->foreign('email_id', 'email_send_email_id' . $this->generateRandomSuffix())->references('id')->on('emails');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_role_user');
        Schema::drop('role_user');
		Schema::drop('roles');
        Schema::drop('email_send');
        Schema::drop('property_types');
		Schema::drop('properties');
        Schema::drop('messages');
        Schema::drop('emails');
        Schema::drop('levels');
        Schema::drop('records');
        Schema::drop('projects');
    }

	/**
	 * to avoid collisions in foreign key names
	 */
	private function generateRandomSuffix() {
		$hash = md5(microtime());
		return '_' . substr($hash,0, 5);
	}
}
