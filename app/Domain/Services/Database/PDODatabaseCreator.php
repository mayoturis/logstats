<?php  namespace Logstats\Domain\Services\Database;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Mayoturis\Properties\RepositoryInterface;

class PDODatabaseCreator implements DatabaseCreator{

	/**
	 *
	 */
	private $envConfig;
	private $connection;

	/**
	 * @param RepositoryInterface $envConfig
	 */
	public function __construct(RepositoryInterface $envConfig, \PDO $connection) {
		$this->envConfig = $envConfig;
		$this->connection = $connection;
	}

	public function createDatabaseIfNotExists() {
		$dbType = $this->envConfig->get('DB_TYPE');
		$dbName = $this->envConfig->get('DB_DATABASE');
		switch ($dbType) {
			case DatabaseType::MYSQL:
			case DatabaseType::SQLITE:
				$this->createWithIf($dbName);
				break;
			case DatabaseType::MSSQL:
				$this->createMssqlDatabase($dbName);
				break;
			case DatabaseType::POSTGRESQL:
				$this->createPgsqlDatabaseIfNotExists($dbName);
				break;
			default:
				throw new InvalidArgumentException('Not supported database type: ' . $dbType);
		}
	}

	protected function createWithIf($dbName) {
		$this->connection->query('CREATE DATABASE IF NOT EXISTS ' .$dbName);
		dd('CREATE DATABASE IF NOT EXISTS ' .$dbName);
	}

	// netestované
	protected function createMssqlDatabaseIfNotExists($dbName) {
		$this->connection->query('if db_id(\'' .$this->connection->quote($dbName).'\') is not null
   					CREATE DATABASE ' .$this->connection->quote($dbName));
	}

	// netestované
	protected function createPgsqlDatabaseIfNotExists($dbName) {
		$this->connection->query('DO
			$do$
			BEGIN

			IF NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = \'' .$this->connection->quote($dbName).'\') THEN
			   PERFORM dblink_exec(\'dbname=\' || current_database()  -- current db
								 , \'CREATE DATABASE mydb\');
			END IF;

			END
			$do$');
	}
}