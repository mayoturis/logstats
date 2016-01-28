<?php  namespace Logstats\Domain\Filters;

class OneValueFilter {

	protected $value;

	/**
	 * @param mixed $value Value against which will filter filter
	 */
	public function __construct($value) {
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getComparisonValue() {
		return $this->value;
	}
}