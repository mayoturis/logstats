<?php  namespace Logstats\Domain\Filters\Factories;
use Logstats\Domain\Filters\StringFilters\ContainsFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\StringFilters\GreaterOrEqualToFilter;
use Logstats\Domain\Filters\StringFilters\GreaterThanFilter;
use Logstats\Domain\Filters\StringFilters\LessOrEqualToFilter;
use Logstats\Domain\Filters\StringFilters\LessThanFilter;
use Logstats\Domain\Filters\StringFilters\NotContainsFilter;
use Logstats\Domain\Filters\StringFilters\NotEqualToFilter;
use Logstats\Domain\Filters\StringFilters\StartsWithFilter;

class StringFilterFactory {
	public function make($value, $comparisonType) {
		switch ($comparisonType) {
			case 'equal':
				return new EqualToFilter($value);
			case 'not-equal':
				return new NotEqualToFilter($value);
			case 'less':
				return new LessThanFilter($value);
			case 'greater':
				return new GreaterThanFilter($value);
			case 'greater-equal':
				return new GreaterOrEqualToFilter($value);
			case 'less-equal':
				return new LessOrEqualToFilter($value);
			case 'contains':
				return new ContainsFilter($value);
			case 'not-contains':
				return new NotContainsFilter($value);
			case 'starts-with':
				return new StartsWithFilter($value);
			default:
				throw new \InvalidArgumentException('Invalid filter type: '.$comparisonType);
		}
	}
}