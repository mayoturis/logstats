<?php  namespace Logstats\Domain\Filters\Factories;
class GeneralFilterFactory {

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