<?php  namespace Logstats\Services\Database; 

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
		$this->artisan->call('migrate');
	}
}