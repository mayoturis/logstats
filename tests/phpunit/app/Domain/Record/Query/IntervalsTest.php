<?php
use Logstats\Domain\Record\Query\Intervals;

class IntervalsTest extends TestCase{
	public function test_all_intervals_can_be_get() {
		$allIntervals = [
			Intervals::MINUTELY,
			Intervals::HOURLY,
			Intervals::DAILY,
			Intervals::MONTHLY,
			Intervals::YEARLY
		];

		$this->assertEquals($allIntervals, Intervals::getAll());
	}
}