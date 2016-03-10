<?php

use Carbon\Carbon;
use Logstats\Domain\Record\Record;

class RecordTest extends TestCase {
	public function test_setId_throws_exception_if_id_is_set() {
		$record = new Record('5', '5', Carbon::now(), 5, []);
		$record->setId(5);

		try {
			$record->setId(10);
			$this->fail('Exception should have been thrown');
		} catch(BadMethodCallException $ex) {
			// ok
		}
	}
}