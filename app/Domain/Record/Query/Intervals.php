<?php  namespace Logstats\Domain\Record\Query;

abstract class Intervals {
	const MINUTELY = "minutely";
	const HOURLY = "hourly";
	const DAILY = "daily";
	const MONTHLY = "monthly";
	const YEARLY = "yearly";

	public static function getAll() {
		return [
			self::MINUTELY,
			self::HOURLY,
			self::DAILY,
			self::MONTHLY,
			self::YEARLY
		];
	}
}