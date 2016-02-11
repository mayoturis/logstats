<?php  namespace Logstats\App\Installation\Database;

use Logstats\App\Installation\Database\Migration\DatabaseMigratorInterface;
use Logstats\App\Installation\Database\Migration\NotAllowedCommandException;

class ArtisanTableCreator implements TableCreator {

	private $databaseMigrator;

	public function __construct(DatabaseMigratorInterface $databaseMigrator) {
		$this->databaseMigrator = $databaseMigrator;
	}

	/**
	 * Migrate database to newest version
	 * @throws \Exception
	 */
	public function migrateDatabase() {
		try {
			$this->databaseMigrator->migrate();
		} catch(NotAllowedCommandException $ex) {
			$this->manualMigrate();
		}
	}

	private function manualMigrate() {
		require_once database_path() . '/migrations/' . '2014_10_12_100000_create_password_resets_table.php';
		require_once database_path() . '/migrations/' . '2015_11_13_134501_init_migration.php';
		require_once database_path() . '/migrations/' . '2015_11_21_222653_add_init_data.php';
		$m1 = new \CreatePasswordResetsTable();
		$m2 = new \InitMigration();
		$m3 = new \AddInitData();
		$m1->up();
		$m2->up();
		$m3->up();
	}
}