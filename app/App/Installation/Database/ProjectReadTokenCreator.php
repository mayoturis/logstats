<?php  namespace Logstats\App\Installation\Database; 

use Logstats\App\Installation\Database\Migration\DatabaseMigratorInterface;
use Logstats\App\Installation\Database\Migration\NotAllowedCommandException;

class ProjectReadTokenCreator implements ProjectReadTokenCreatorInterface {


	private $databaseMigrator;

	public function __construct(DatabaseMigratorInterface $databaseMigrator) {
		$this->databaseMigrator = $databaseMigrator;
	}

	/**
	 * Renames token column to write_token
	 * Adds read_token column to table
	 * Creates read tokens on created projects
	 */
	public function createReadTokens() {
		try {
			$this->databaseMigrator->migrate();
		} catch(NotAllowedCommandException $ex) {
			$this->manualMigrate();
		}
	}

	private function manualMigrate() {
		require_once database_path() . '/migrations/' . '2016_02_09_110338_add_read_project_token.php';
		$m1 = new \AddReadProjectToken();
		$m1->up();
	}
}