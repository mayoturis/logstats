<?php  namespace Logstats\Domain\Services\Database;

interface TableCreator {

	/**
	 * Migrate database to newest version
	 */
	public function migrateDatabase();
}