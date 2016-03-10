<?php

use Logstats\Domain\Record\Query\AggregateFunctions;

class AggregateFunctionsTest extends TestCase {
	public function test_all_aggregate_function_can_be_get() {
		$allAggregateFunctions = [
			AggregateFunctions::SUM,
			AggregateFunctions::AVG,
			AggregateFunctions::MIN,
			AggregateFunctions::MAX,
			AggregateFunctions::COUNT
		];

		$this->assertEquals($allAggregateFunctions, AggregateFunctions::getAll());
	}
}