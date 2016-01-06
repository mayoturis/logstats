<?php  namespace Logstats\Domain\Services\Database;

interface DatabaseConfigServiceInterface {
	public function saveConfiguration(array $data);

	public function checkConfiguration();
}