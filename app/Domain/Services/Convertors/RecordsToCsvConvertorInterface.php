<?php  namespace Logstats\Domain\Services\Convertors; 

use Logstats\Domain\Record\Record;

interface RecordsToCsvConvertorInterface {

	/**
	 * Converts array of records to one string in CSV format
	 *
	 * @param Record[] $records
	 * @return string
	 */
	public function convertRecordsToCsvString(array $records);
}