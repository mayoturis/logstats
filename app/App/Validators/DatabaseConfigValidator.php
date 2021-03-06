<?php  namespace Logstats\App\Validators;

class DatabaseConfigValidator extends AbstractValidator {
	private $databaseTypeRule = [
		'database_type' => 'required|in:mysql,mssql,pgsql,sqlite'
	];

	private $sqliteRules = [
		'database_location' => 'required|string|file_can_be_created'
	];

	private $mysqlRules = [
		'host' => 'required|string',
		'database' => 'required|string',
		'username' => 'required|string',
		'charset' => 'required|string',
		'collation' => 'required|string',
	];

	private $mssqlRules = [
		'host' => 'required|string',
		'database' => 'required|string',
		'username' => 'required|string',
		'charset' => 'required|string',
	];

	private $pgsqlRules = [
		'host' => 'required|string',
		'database' => 'required|string',
		'username' => 'required|string',
		'charset' => 'required|string',
		'schema' => 'required|string',
	];

	public function isValidDatabaseSetup($input) {
		if (!$this->isValid($input, $this->databaseTypeRule)) {
			return false;
		}

		// $input['database_type'] is already valid here
		$rulestring = $input['database_type'] . 'Rules';
		$rules = $this->$rulestring;

		return $this->isValid($input, $rules);
	}
}