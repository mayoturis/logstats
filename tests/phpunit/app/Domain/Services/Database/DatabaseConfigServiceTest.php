<?php

use Logstats\App\Validators\DatabaseConfigValidator;
use Logstats\Domain\Services\Database\DatabaseConfigService;

class DatabaseConfigServiceTest extends TestCase{
	public function test_saveConfiguration_can_save_mysql_configuration() {
		$config = Mockery::mock('Mayoturis\Properties\RepositoryInterface');
		$validator = Mockery::mock(DatabaseConfigValidator::class);
		$dcs = new DatabaseConfigService($config, $validator);
		$data = $this->getMysqlData();

		$validator->shouldReceive('isValidDatabaseSetup')->once()->with($data)->andReturn(true);
		$config->shouldReceive('set')->with('DB_TYPE', $data['database_type']);
		$config->shouldReceive('set')->with('DB_HOST', $data['host']);
		$config->shouldReceive('set')->with('DB_DATABASE', $data['database']);
		$config->shouldReceive('set')->with('DB_USERNAME', $data['username']);
		$config->shouldReceive('set')->with('DB_CHARSET', $data['charset']);
		$config->shouldReceive('set')->with('DB_PASSWORD', '');
		$config->shouldReceive('set')->with('DB_PREFIX', '');
		$config->shouldReceive('set')->with('DB_COLLATION', $data['collation']);

		$dcs->saveConfiguration($data);

		$this->assertTrue(true);
	}

	public function test_saveConfiguration_can_save_sqlite_configuration() {
		$config = Mockery::mock('Mayoturis\Properties\RepositoryInterface');
		$validator = Mockery::mock(DatabaseConfigValidator::class);
		$dcs = new DatabaseConfigService($config, $validator);
		$data = $this->getSqliteData();

		$validator->shouldReceive('isValidDatabaseSetup')->once()->with($data)->andReturn(true);
		$config->shouldReceive('set')->with('DB_TYPE', $data['database_type']);
		$config->shouldReceive('set')->with('DB_PREFIX', '');
		$config->shouldReceive('set')->with('DB_DATABASE_PATH', $data['database_location']);

		$dcs->saveConfiguration($data);

		$this->assertTrue(true);
	}

	public function test_saveConfiguration_can_save_mssql_configution() {
		$config = Mockery::mock('Mayoturis\Properties\RepositoryInterface');
		$validator = Mockery::mock(DatabaseConfigValidator::class);
		$dcs = new DatabaseConfigService($config, $validator);
		$data = $this->getMssqlData();

		$validator->shouldReceive('isValidDatabaseSetup')->once()->with($data)->andReturn(true);
		$config->shouldReceive('set')->with('DB_TYPE', $data['database_type']);
		$config->shouldReceive('set')->with('DB_HOST', $data['host']);
		$config->shouldReceive('set')->with('DB_DATABASE', $data['database']);
		$config->shouldReceive('set')->with('DB_USERNAME', $data['username']);
		$config->shouldReceive('set')->with('DB_CHARSET', $data['charset']);
		$config->shouldReceive('set')->with('DB_PASSWORD', '');
		$config->shouldReceive('set')->with('DB_PREFIX', '');

		$dcs->saveConfiguration($data);

		$this->assertTrue(true);
	}

	public function test_saveConfiguration_can_save_pgsql_configuration() {
		$config = Mockery::mock('Mayoturis\Properties\RepositoryInterface');
		$validator = Mockery::mock(DatabaseConfigValidator::class);
		$dcs = new DatabaseConfigService($config, $validator);
		$data = $this->getPgsqlData();

		$validator->shouldReceive('isValidDatabaseSetup')->once()->with($data)->andReturn(true);
		$config->shouldReceive('set')->with('DB_TYPE', $data['database_type']);
		$config->shouldReceive('set')->with('DB_HOST', $data['host']);
		$config->shouldReceive('set')->with('DB_DATABASE', $data['database']);
		$config->shouldReceive('set')->with('DB_USERNAME', $data['username']);
		$config->shouldReceive('set')->with('DB_CHARSET', $data['charset']);
		$config->shouldReceive('set')->with('DB_PASSWORD', '');
		$config->shouldReceive('set')->with('DB_PREFIX', '');
		$config->shouldReceive('set')->with('DB_SCHEMA', $data['schema']);

		$dcs->saveConfiguration($data);

		$this->assertTrue(true);
	}

	protected function getMysqlData() {
		return [
			"database_type" => "mysql",
			"host" => "host",
			"database" => "database",
			"username" => "username",
			"charset" => "charset",
			"collation" => "charset",
		];
	}

	protected function getSqliteData() {
		return [
			"database_type" => "sqlite",
			"database_location" => "locaiton",
		];
	}

	protected function getMssqlData() {
		return [
			"database_type" => "mssql",
			"host" => "host",
			"database" => "database",
			"username" => "username",
			"charset" => "charset",
		];
	}

	private function getPgsqlData() {
		return [
			"database_type" => "pgsql",
			"host" => "host",
			"database" => "database",
			"username" => "username",
			"charset" => "charset",
			"schema" => "schema"
		];
	}
}