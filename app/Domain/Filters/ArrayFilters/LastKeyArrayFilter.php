<?php  namespace Logstats\Domain\Filters\ArrayFilters;

use Logstats\Domain\Filters\BooleanFilters\BooleanFilter;
use Logstats\Domain\Filters\NumberFilters\NumberFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;

class LastKeyArrayFilter implements ArrayFilter {

	private $lastKey;
	private $filter;

	/**
	 * @param string $lastKey
	 * @param BooleanFilter|StringFilter|NumberFilter $filter
	 */
	public function __construct($lastKey, $filter) {
		$this->lastKey = $lastKey;
		$this->filter = $filter;
	}

	public function match(array $array) {

	}

	/**
	 * @return mixed
	 */
	public function getLastKey() {
		return $this->lastKey;
	}

	/**
	 * @return mixed
	 */
	public function getFilter() {
		return $this->filter;
	}
}