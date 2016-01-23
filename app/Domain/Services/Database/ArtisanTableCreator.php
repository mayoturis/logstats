<?php  namespace Logstats\Domain\Services\Database;

use Illuminate\Contracts\Console\Kernel;

class ArtisanTableCreator implements TableCreator{

	/**
	 *	Artisan
	 */
	private $artisan;

	/**
	 * @param Kernel $artisan
	 */
	public function __construct(Kernel $artisan) {
		$this->artisan = $artisan;
	}

	public function migrateDatabase() {
		try {
			$this->artisan->call('migrate');
		} catch(\Exception $ex) {
			if ($this->isExceptionFromNotAllowedCommandCall($ex)) {
				$this->manualMigrate();
			} else {
				throw $ex;
			}
		}
	}

	private function isExceptionFromNotAllowedCommandCall(\Exception $e) {
		return strpos($e->getMessage(), 'escapeshellarg') !== false;
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