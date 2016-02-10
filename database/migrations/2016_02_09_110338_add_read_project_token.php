<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddReadProjectToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function(Blueprint $table) {
			$table->renameColumn('token', 'write_token');
			$table->index('write_token', 'write_token_index');
			$table->string('read_token')->index();
		});

		$projects = DB::table('projects')->get();
		foreach ($projects as $project) {
			DB::table('projects')->where('id', $project->id)->update([
				'read_token' => $this->uniqueReadTokenForName($project->name)
			]);
		}
    }

	private function uniqueReadTokenForName($name) {
		return 'r' . preg_replace('/\s+/', '', $name) . substr(md5(microtime(uniqid())), 0, 10);
	}


	/**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('projects', function(Blueprint $table) {
			$table->renameColumn('write_token', 'token');
			$table->dropIndex('write_token_index');
			$table->dropColumn('read_token');
		});
    }
}
