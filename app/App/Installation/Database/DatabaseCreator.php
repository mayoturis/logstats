<?php  namespace Logstats\App\Installation\Database;

interface DatabaseCreator {
	public function createDatabaseIfNotExists();
}