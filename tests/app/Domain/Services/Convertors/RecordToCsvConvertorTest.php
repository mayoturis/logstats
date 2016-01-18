<?php

use Carbon\Carbon;
use Logstats\Domain\Record\Record;
use Logstats\Domain\Services\Convertors\RecordsToCsvConvertor;

class RecordToCsvConvertorTest extends TestCase {
	public function test_it_converts_record_array_to_csv_string() {
		$recordToCsvConvertor = new RecordsToCsvConvertor();
		$now = Carbon::now();
		$record1 = new Record('level1', 'message1', $now, 1);
		$record2 = new Record('level2', 'messá"ge2', $now, 2, ['car' => '\'ščťž']);

		$string = $recordToCsvConvertor->convertRecordsToCsvString([$record1, $record2]);
		$expectedString = '"'.$now.'","level1","message1",""' . "\r\n"
						. '"'.$now.'","level2","messá""ge2","{""car"":""\'ščťž""}"';

		$this->assertEquals($string, $expectedString);
	}
}