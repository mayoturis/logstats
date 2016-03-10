<?php

use Logstats\Domain\Filters\ComparisonTypes;

class ComparisonTypesTest {
	public function test_all_comparison_types_can_be_get() {
		$allComparisonTypes = [
			ComparisonTypes::EQUAL_TO,
			ComparisonTypes::NOT_EQUAL_TO,
			ComparisonTypes::GREATER_THAN,
			ComparisonTypes::LESS_THAN,
			ComparisonTypes::GREATER_OR_EQUAL_TO,
			ComparisonTypes::LESS_THAN_OR_EQUAL_TO,
			ComparisonTypes::CONTAINS,
			ComparisonTypes::NOT_CONTAINS
		];
	}
}