<?php  namespace Logstats\Domain\Filters\NumberFilters;

use Logstats\Domain\Filters\OneValueFilter;

class NotEqualToFilter extends OneValueFilter implements NumberFilter{

	/**
	 * @param int|float $number
	 * @return bool
	 */
	public function match($number) {

	}
}