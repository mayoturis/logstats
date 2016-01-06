<?php  namespace Logstats\Domain\Filters\Factories;
use Logstats\Domain\Filters\BooleanFilters\EqualToFilter;

class BooleanFilterFactory {
	public function make($value, $comparisonType) {
		switch ($comparisonType) {
			case 'equal':
				return new EqualToFilter($value);
			default:
				throw new \InvalidArgumentException('Invalid filter type: '.$comparisonType);
		}
	}
}