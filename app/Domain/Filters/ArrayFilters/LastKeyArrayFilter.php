<?php  namespace Logstats\Domain\Filters\ArrayFilters;

class LastKeyArrayFilter implements ArrayFilter{

	private $lastKey;
	private $filter;

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