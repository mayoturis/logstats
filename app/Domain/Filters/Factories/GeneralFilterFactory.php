<?php  namespace Logstats\Domain\Filters\Factories;
use Logstats\Domain\Filters\BooleanFilters\BooleanFilter;
use Logstats\Domain\Filters\NumberFilters\NumberFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;

class GeneralFilterFactory {

	/**
	 * Creates filter
	 *
	 * @param string|int|bool $value Value against which will filter filter
	 * @param string $variableType
	 * @param string $comparisonType String representation of comparison,
	 * 								 one of ComparisonTypes contstants
	 * @throws InvalidArgumentException if given comparison type or variable type is not supported
	 * @return BooleanFilter|StringFilter|NumberFilter
	 */
	public function make($value, $variableType, $comparisonType) {
		$boleanff = new BooleanFilterFactory();
		$stringff = new StringFilterFactory();
		$numberff = new NumberFilterFactory();

		switch($variableType) {
			case "string":
				return $stringff->make($value, $comparisonType);
			case "number":
				return $numberff->make($value, $comparisonType);
			case "boolean":
				return $boleanff->make($value, $comparisonType);
			default:
				throw new \InvalidArgumentException('Unsupported variable type: '.$variableType);
		}
	}
}