<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInitData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')
			->insert([
				['name' => 'admin'],
				['name' => 'datamanager'],
				['name' => 'visitor'],
			]);

		DB::table('levels')
			->insert([
				['name' => 'debug'],
				['name' => 'info'],
				['name' => 'notice'],
				['name' => 'warning'],
				['name' => 'error'],
				['name' => 'critical'],
				['name' => 'alert'],
				['name' => 'emergency'],
			]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		DB::table('roles')->where('name', 'admin')->delete();
		DB::table('roles')->where('name', 'datamanager')->delete();
		DB::table('roles')->where('name', 'visitor')->delete();

		DB::table('levels')->where('name', 'debug')->delete();
		DB::table('levels')->where('name', 'info')->delete();
		DB::table('levels')->where('name', 'notice')->delete();
		DB::table('levels')->where('name', 'warning')->delete();
		DB::table('levels')->where('name', 'error')->delete();
		DB::table('levels')->where('name', 'critical')->delete();
		DB::table('levels')->where('name', 'alert')->delete();
		DB::table('levels')->where('name', 'emergency')->delete();
    }
}
