<?php  namespace Logstats\Domain\Services\Database;

interface TableCreator {

	/**
	 * Migrate database to newest version
	 * @throws \Exception
	 */
	public function migrateDatabase();
}