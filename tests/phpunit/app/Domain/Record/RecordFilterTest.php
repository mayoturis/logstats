<?php

use Carbon\Carbon;
use Logstats\Domain\Filters\TimeFilters\FromFilter;
use Logstats\Domain\Record\RecordFilter;

class RecordFilterTest extends TestCase {
	public function test_time_filter_can_be_added() {
		$recordFilter = new RecordFilter();
		$recordFilter->addDateFilter(new FromFilter(Carbon::now()));

		$this->assertEquals(1, count($recordFilter->getDateFilters()));
	}

}