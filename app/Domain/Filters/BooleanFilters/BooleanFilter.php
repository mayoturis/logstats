<?php  namespace Logstats\Domain\Filters\BooleanFilters; 

interface BooleanFilter {

	/**
	 * @param bool $boolean
	 * @return bool
	 */
	public function match($boolean);
}