<?php  namespace Logstats\Services\Database; 

interface TableCreator {

	/**
	 * Migrate database to newest version
	 */
	public function migrateDatabase();
}