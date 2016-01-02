<?php  namespace Logstats\Domain\Filters\StringFilters; 

use Logstats\Domain\Filters\OneValueFilter;

class StartsWithFilter extends OneValueFilter implements StringFilter{

	/**
	 * @param string $string
	 * @return bool
	 */
	public function match($string) {

	}
}