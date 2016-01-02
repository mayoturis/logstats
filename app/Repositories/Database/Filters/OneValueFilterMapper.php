<?php  namespace Logstats\Repositories\Database\Filters;

use Logstats\Domain\Filters\OneValueFilter;
use Logstats\Domain\Filters\BooleanFilters\BooleanFilter;
use Logstats\Domain\Filters\NumberFilters\NumberFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;
use Logstats\Domain\Filters\TimeFilters\TimeFilter;
use Logstats\Services\Date\CarbonConvertorInterface;

class OneValueFilterMapper {

	private $carbonConvertor;

	public function __construct(CarbonConvertorInterface $carbonConvertor) {
		$this->carbonConvertor = $carbonConvertor;
	}

	public function getValue(OneValueFilter $filter) {
		if($filter instanceof BooleanFilter) {
				return $this->getBooleanValue($filter->getComparisonValue());
		}
		if ($filter instanceof \Logstats\Domain\Filters\StringFilters\ContainsFilter ||
			$filter instanceof \Logstats\Domain\Filters\StringFilters\NotContainsFilter) {
			return "%".$filter->getComparisonValue()."%";
		}

		if ($filter instanceof \Logstats\Domain\Filters\StringFilters\StartsWithFilter) {
			return $filter->getComparisonValue()."%";
		}

		if ($filter instanceof TimeFilter) {
			return (string) $this->carbonConvertor->carbonInGMT($filter->getComparisonValue());
		}
		return $filter->getComparisonValue();
	}

	public function getType(OneValueFilter $filter) {
		switch (true) {
			case ($filter instanceof StringFilter):
				return 'string';
			case ($filter instanceof NumberFilter):
				return 'number';
			case ($filter instanceof BooleanFilter):
				return 'boolean';
		}
	}

	public function getComparisonOperator(OneValueFilter $filter) {
		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\EqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\EqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\BooleanFilters\EqualToFilter)) {
			return "=";
		}

		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\GreaterOrEqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\GreaterOrEqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\TimeFilters\FromFilter)) {
			return ">=";
		}

		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\GreaterThanFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\GreaterThanFilter)) {
			return ">";
		}

		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\LessOrEqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\LessOrEqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\TimeFilters\ToFilter)) {
			return "<=";
		}

		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\LessThanFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\LessThanFilter)) {
			return "<";
		}

		if (($filter instanceof \Logstats\Domain\Filters\StringFilters\NotEqualToFilter) ||
			($filter instanceof \Logstats\Domain\Filters\NumberFilters\NotEqualToFilter)) {
			return "<>";
		}

		if ($filter instanceof \Logstats\Domain\Filters\StringFilters\ContainsFilter ||
			($filter instanceof \Logstats\Domain\Filters\StringFilters\StartsWithFilter)) {
			return "LIKE";
		}

		if ($filter instanceof \Logstats\Domain\Filters\StringFilters\NotContainsFilter) {
			return "NOT LIKE";
		}

		throw new \InvalidArgumentException('Not supported filter');
	}

	private function getBooleanValue($value) {
		if ($value == '0' || $value == 'false') {
			return 0;
		}

		return 1;
	}
}