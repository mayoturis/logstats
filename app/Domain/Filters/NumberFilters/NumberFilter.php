<?php  namespace Logstats\Domain\Filters\NumberFilters;
interface NumberFilter {

	/**
	 * @param int|float $number
	 * @return bool
	 */
	public function match($number);
}