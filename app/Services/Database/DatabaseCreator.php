<?php  namespace Logstats\Services\Database; 

interface DatabaseCreator {
	public function createDatabaseIfNotExists();
}