<?php  namespace Logstats\Repositories\Contracts; 

use Logstats\Entities\Record;

interface DataRepository {

	/**
	 * @param Record $record
	 * @return void
	 */
	public function newRecord(Record $record);
}