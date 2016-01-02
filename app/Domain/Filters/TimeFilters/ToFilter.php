<?php  namespace Logstats\Domain\Filters\TimeFilters; 

use Carbon\Carbon;
use Logstats\Domain\Filters\OneValueFilter;

class ToFilter extends OneValueFilter implements TimeFilter {


	public function __construct(Carbon $date) {
		$this->value = $date;
	}

	public function match(Carbon $time) {

	}
}