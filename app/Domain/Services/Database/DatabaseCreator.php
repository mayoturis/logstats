<?php  namespace Logstats\Domain\Services\Database;

interface DatabaseCreator {
	public function createDatabaseIfNotExists();
}