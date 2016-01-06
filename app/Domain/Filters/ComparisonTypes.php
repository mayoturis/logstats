<?php  namespace Logstats\Domain\Filters;

class ComparisonTypes {
	const EQUAL_TO = "equal";
	const NOT_EQUAL_TO = "not_equal";
	const GREATER_THAN = "greater";
	const LESS_THAN = "less";
	const GREATER_OR_EQUAL_TO = "greater_or_equal";
	const LESS_THAN_OR_EQUAL_TO = "less_or_equal";
	const CONTAINS = "contains";
	const NOT_CONTAINS = "not_contains";

	public static function getAll() {
		return [
			self::EQUAL_TO,
			self::NOT_EQUAL_TO,
			self::GREATER_THAN,
			self::LESS_THAN,
			self::GREATER_OR_EQUAL_TO,
			self::LESS_THAN_OR_EQUAL_TO,
			self::CONTAINS,
			self::NOT_CONTAINS
		];
	}
}