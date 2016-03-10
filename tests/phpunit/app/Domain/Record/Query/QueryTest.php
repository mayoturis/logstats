<?php
use Carbon\Carbon;
use Logstats\Domain\Record\Query\Query;

class QueryTest extends TestCase {
	public function test_correct_timeframe_is_returned() {
		$time1 = Carbon::now()->subDays(1);
		$time2 = Carbon::now();

		$query = new Query();
		$query->setFrom($time1);
		$query->setTo($time2);

		$timeframe = $query->getTimeFrame();

		$this->assertEquals($time1->timestamp, $timeframe['from']);
		$this->assertEquals($time2->timestamp, $timeframe['to']);
	}
}