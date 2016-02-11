<?php  namespace Logstats\App\Installation\Database\Migration;

use Illuminate\Contracts\Console\Kernel;

class DatabaseMigrator implements DatabaseMigratorInterface {

	private $artisan;

	/**
	 * @param Kernel $artisan
	 */
	public function __construct(Kernel $artisan) {
		$this->artisan = $artisan;
	}

	public function migrate() {
		try {
			$this->artisan->call('migrate');
		} catch(\Exception $ex) {
			if ($this->isExceptionFromNotAllowedCommandCall($ex)) {
				throw new NotAllowedCommandException('Not allowed command',0,$ex);
			} else {
				throw $ex;
			}
		}
	}

	private function isExceptionFromNotAllowedCommandCall(\Exception $e) {
		return strpos($e->getMessage(), 'escapeshellarg') !== false;
	}
}