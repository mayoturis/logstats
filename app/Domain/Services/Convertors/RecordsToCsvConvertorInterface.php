<?php  namespace Logstats\Domain\Services\Convertors; 

use Logstats\Domain\Record\Record;

interface RecordsToCsvConvertorInterface {

	/**
	 * @param Record[] $records
	 * @return string
	 */
	public function convertRecordsToCsvString(array $records);
}