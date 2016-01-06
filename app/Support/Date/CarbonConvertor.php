<?php  namespace Logstats\Support\Date;

use Carbon\Carbon;

class CarbonConvertor implements CarbonConvertorInterface {

	/**
	 * @param int $timestamp
	 * @return Carbon
	 */
	public function carbonFromTimestampUTC($timestamp) {
		$timezone = Carbon::now()->getTimezone();
		$carbon = Carbon::createFromTimestampUTC($timestamp);
		$carbon->setTimezone($timezone);
		return $carbon;
	}

	/**
	 * @param $string
	 * @return Carbon
	 */
	public function carbonFromStandartGMTString($string) {
		$t = Carbon::now();
		$tz = $t->getTimezone();
		$carbon = Carbon::createFromFormat('Y-m-d H:i:s', $string, 'GMT');
		$carbon->setTimezone($tz);
		return $carbon;
	}

	/**
	 * @param Carbon $date
	 * @return Carbon
	 */
	public function carbonInGMT(Carbon $date) {
		$new = Carbon::createFromFormat('Y-m-d H:i:s', $date);
		$new->setTimezone('GMT');
		return $new;
	}
}