<?php  namespace Logstats\Domain\Filters\TimeFilters;

use Carbon\Carbon;
use Logstats\Domain\Filters\OneValueFilter;

class ToFilter extends OneValueFilter implements TimeFilter {


	/**
	 * @param Carbon $date Date against which will filter filter
	 */
	public function __construct(Carbon $date) {
		$this->value = $date;
	}

	/**
	 * @param Carbon $time
	 * @return bool
	 */
	//public function match(Carbon $time) {}
}