<?php  namespace Logstats\Services\Database;

interface DatabaseConfigServiceInterface {
	public function saveConfiguration(array $data);

	public function checkConfiguration();
}