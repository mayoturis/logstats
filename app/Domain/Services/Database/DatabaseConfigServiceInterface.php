<?php  namespace Logstats\Domain\Services\Database;

interface DatabaseConfigServiceInterface {

	/**
	 * Save database configuration
	 *
	 * @param array $data Configuration data
	 */
	public function saveConfiguration(array $data);
}