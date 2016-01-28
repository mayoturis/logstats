<?php  namespace Logstats\Domain\Filters\Factories;
use Logstats\Domain\Filters\BooleanFilters\BooleanFilter;
use Logstats\Domain\Filters\BooleanFilters\EqualToFilter;

class BooleanFilterFactory {

	/**
	 * Creates boolean filter
	 *
	 * @param bool $value Value against which will filter filter
	 * @param string $comparisonType String representation of comparison,
	 * 								 one of ComparisonTypes contstants
	 * @throws InvalidArgumentException if given comparison type is not supported
	 * @return BooleanFilter
	 */
	public function make($value, $comparisonType) {
		switch ($comparisonType) {
			case 'equal':
				return new EqualToFilter($value);
			default:
				throw new \InvalidArgumentException('Invalid filter type: '.$comparisonType);
		}
	}
}