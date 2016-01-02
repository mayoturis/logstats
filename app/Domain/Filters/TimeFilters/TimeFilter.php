<?php  namespace Logstats\Domain\Filters\TimeFilters; 

use Carbon\Carbon;

interface TimeFilter {

	/**
	 * @param Carbon $time
	 * @return bool
	 */
	public function match(Carbon $time);
}