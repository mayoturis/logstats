<?php  namespace Logstats\Support\Date;

use Carbon\Carbon;

interface CarbonConvertorInterface {

	/**
	 * Creates Carbon instance from unix timestamp
	 *
	 * @param int $timestamp
	 * @return Carbon
	 */
	public function carbonFromTimestampUTC($timestamp);

	/**
	 * Creates Carbon instance from string in GMT timezone
	 *
	 * @param $string
	 * @return Carbon
	 */
	public function carbonFromStandartGMTString($string);

	/**
	 * Creates Carbon instance with GMT timezone
	 *
	 * @param Carbon $date
	 * @return Carbon
	 */
	public function carbonInGMT(Carbon $date);
}