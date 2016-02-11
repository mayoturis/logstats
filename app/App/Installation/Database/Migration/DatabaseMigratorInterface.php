<?php  namespace Logstats\App\Installation\Database\Migration; 

interface DatabaseMigratorInterface {
	public function migrate();
}