<?php  namespace Logstats\Services\Validators; 

class DatabaseConfigValidator extends AbstractValidator {
	private $databaseTypeRule = [
		'database_type' => 'required|in:mysql,mssql,pgsql,sqlite'
	];

	private $sqliteRules = [
		'database_location' => 'required|file_can_be_created'
	];

	private $mysqlRules = [
		'host' => 'required',
		'database' => 'required',
		'username' => 'required',
		'charset' => 'required',
		'collation' => 'required',
	];

	private $mssqlRules = [
		'host' => 'required',
		'database' => 'required',
		'username' => 'required',
		'charset' => 'required',
	];

	private $pgsqlRules = [
		'host' => 'required',
		'database' => 'required',
		'username' => 'required',
		'charset' => 'required',
		'schema' => 'required',
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