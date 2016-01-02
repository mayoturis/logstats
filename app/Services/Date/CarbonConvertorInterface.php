<?php  namespace Logstats\Services\Date; 

use Carbon\Carbon;

interface CarbonConvertorInterface {

	/**
	 * @param int $timestamp
	 * @return Carbon
	 */
	public function carbonFromTimestampUTC($timestamp);

	/**
	 * @param $string
	 * @return Carbon
	 */
	public function carbonFromStandartGMTString($string);

	/**
	 * @param Carbon $date
	 * @return Carbon
	 */
	public function carbonInGMT(Carbon $date);
}