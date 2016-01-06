<?php  namespace Logstats\Infrastructure\Repositories\Database\Filters;

use Logstats\Domain\Filters\ComparisonTypes;

class ComparisonTypeMapper {
	const EQUAL_TO = "equal";
	const NOT_EQUAL_TO = "not_equal";
	const GREATER_THAN = "greater";
	const LESS_THAN = "less";
	const GREATER_OR_EQUAL_TO = "greater_or_equal";
	const LESS_THAN_OR_EQUAL_TO = "less_or_equal";
	const CONTAINS = "contains";
	const NOT_CONTAINS = "not_contains";

	public function getOperator($operator) {
		$mappings = $this->getMappingsOperatorMappings();
		return $mappings[$operator];
	}

	public function getValue($operator, $value) {
		if ($operator == ComparisonTypes::CONTAINS ||
			$operator == ComparisonTypes::NOT_CONTAINS) {
			return "%".$value."%";
		}

		return $value;
	}

	private function getMappingsOperatorMappings() {
		return [
			ComparisonTypes::EQUAL_TO => '=',
			ComparisonTypes::NOT_EQUAL_TO => '<>',
			ComparisonTypes::GREATER_THAN => '>',
			ComparisonTypes::LESS_THAN => '<',
			ComparisonTypes::GREATER_OR_EQUAL_TO => '>=',
			ComparisonTypes::LESS_THAN_OR_EQUAL_TO => '<=',
			ComparisonTypes::CONTAINS => 'LIKE',
			ComparisonTypes::NOT_CONTAINS => 'NOT LIKE',
		];
	}
}