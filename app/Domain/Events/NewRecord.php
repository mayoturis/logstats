<?php  namespace Logstats\Domain\Events; 

use Logstats\Domain\Record\Record;

class NewRecord {

	private $record;

	/**
	 * @param Record $record
	 */
	public function __construct(Record $record) {
		$this->record = $record;
	}

	/**
	 * @return Record
	 */
	public function getRecord() {
		return $this->record;
	}
}