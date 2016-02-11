<?php  namespace Logstats\App\Installation\Database;

interface TableCreator {

	/**
	 * Migrate database to newest version
	 * @throws \Exception
	 */
	public function migrateDatabase();
}