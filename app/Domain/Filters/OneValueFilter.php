<?php  namespace Logstats\Domain\Filters; 

class OneValueFilter {

	protected $value;

	public function __construct($value) {
		$this->value = $value;
	}

	public function getComparisonValue() {
		return $this->value;
	}
}