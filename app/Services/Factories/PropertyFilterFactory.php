<?php  namespace Logstats\Services\Factories; 

use Logstats\ValueObjects\PropertyFilters\ContainsFilter;
use Logstats\ValueObjects\PropertyFilters\EqualToFilter;
use Logstats\ValueObjects\PropertyFilters\GreaterOrEqualToFilter;
use Logstats\ValueObjects\PropertyFilters\GreaterThanFilter;
use Logstats\ValueObjects\PropertyFilters\LessOrEqualToFilter;
use Logstats\ValueObjects\PropertyFilters\LessThanFilter;
use Logstats\ValueObjects\PropertyFilters\NotContainsFilter;
use Logstats\ValueObjects\PropertyFilters\NotEqualToFilter;

class PropertyFilterFactory {
	/**
	 * @param $propertyName
	 * @param $propertyValue
	 * @param $propertyType
	 * @param $comparisonType
	 * @return FilterInterface
	 */
	public function make($propertyName,$propertyValue,$propertyType, $comparisonType) {
		switch ($comparisonType) {
			case 'equal':
				return new EqualToFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'not-equal':
				return new NotEqualToFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'less':
				return new LessThanFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'greater':
				return new GreaterThanFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'greater-equal':
				return new GreaterOrEqualToFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'less-equal':
				return new LessOrEqualToFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'contains':
				return new ContainsFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			case 'not-contains':
				return new NotContainsFilter($propertyName, $propertyValue,$propertyType, $comparisonType);
			default:
				throw new \InvalidArgumentException('Invalid filter type: '.$comparisonType);
		}
	}
}