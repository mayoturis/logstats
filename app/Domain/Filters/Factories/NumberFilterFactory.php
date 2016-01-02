<?php  namespace Logstats\Domain\Filters\Factories; 
use InvalidArgumentException;
use Logstats\Domain\Filters\NumberFilters\EqualToFilter;
use Logstats\Domain\Filters\NumberFilters\GreaterOrEqualToFilter;
use Logstats\Domain\Filters\NumberFilters\GreaterThanFilter;
use Logstats\Domain\Filters\NumberFilters\LessOrEqualToFilter;
use Logstats\Domain\Filters\NumberFilters\LessThanFilter;
use Logstats\Domain\Filters\NumberFilters\NotEqualToFilter;

class NumberFilterFactory {
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
			default:
				throw new InvalidArgumentException('Invalid filter type: '.$comparisonType);
		}
	}
}