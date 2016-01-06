<?php  namespace Logstats\Domain\Filters\StringFilters;

interface StringFilter {

	/**
	 * @param string $string
	 * @return bool
	 */
	public function match($string);
}