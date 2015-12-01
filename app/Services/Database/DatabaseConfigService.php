<?php  namespace Logstats\Services\Database;

use Logstats\Services\Validators\DatabaseConfigValidator;
use Logstats\Services\Validators\ValidationException;
use Mayoturis\Properties\RepositoryInterface;

class DatabaseConfigService implements DatabaseConfigServiceInterface{

	/**
	 *  Configuration class
	 */
	private $config;
	/**
	 * Installation validator
	 */
	private $validator;

	/**
	 * @param \Mayoturis\Properties\RepositoryInterface $config
	 * @param  InstallationValidator $validator
	 */
	public function __construct(RepositoryInterface $config, DatabaseConfigValidator $validator) {
		$this->config = $config;
		$this->validator = $validator;
	}

	/**
	 * @param array $data Configuration data
	 */
	public function saveConfiguration(array $data) {
		if (!$this->validator->isValidDatabaseSetup($data)) {
			throw new ValidationException($this->validator->getErrors());
		}

		$this->config->set('DB_TYPE', $data['database_type']);
		switch($data['database_type']) {
			case "mysql":
				$this->saveMysqlConfiguration($data);
				break;
			case "mssql":
				$this->saveMssqlConfiguration($data);
				break;
			case "pgsql":
				$this->savePgsqlConfiguration($data);
				break;
			case "sqlite":
				$this->saveSqliteConfiguration($data);
				break;
		}
	}

	public function checkConfiguration() {

	}

	/**
	 * @param array $data Configuration data
	 */
	private function saveMysqlConfiguration(array $data) {
		$this->saveBasicConfig($data);
		$this->config->set('DB_COLLATION', $data['collation']);
	}

	/**
	 * @param array $data Configuration data
	 */
	private function saveMssqlConfiguration(array $data) {
		$this->saveBasicConfig($data);
	}

	/**
	 * @param array $data Configuration data
	 */
	private function savePgsqlConfiguration(array $data) {
		$this->saveBasicConfig($data);
		$this->config->set('DB_SCHEMA', $data['schema']);
	}

	/**
	 * @param array $data Configuration service
	 */
	private function saveSqliteConfiguration(array $data) {
		$data['prefix'] = isset($data['prefix']) ? $data['prefix'] : '';
		$this->config->set('DB_PREFIX', $data['prefix']);
		$this->config->set('DB_DATABASE_PATH', $data['database_location']);
	}

	private function saveBasicConfig(array $data) {
		$this->config->set('DB_HOST', $data['host']);
		$this->config->set('DB_DATABASE', $data['database']);
		$this->config->set('DB_USERNAME', $data['username']);
		$this->config->set('DB_CHARSET', $data['charset']);

		$data['password'] = isset($data['password']) ? $data['password'] : '';
		$data['prefix'] = isset($data['prefix']) ? $data['prefix'] : '';

		$this->config->set('DB_PASSWORD', $data['password']);
		$this->config->set('DB_PREFIX', $data['prefix']);
	}
}