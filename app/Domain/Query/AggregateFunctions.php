<?php  namespace Logstats\Domain\Query; 

abstract class AggregateFunctions {
	const SUM = "sum";
	const AVG = "avg";
	const MIN = "min";
	const MAX = "max";
	const COUNT = "count";

	public static function getAll() {
		return [
			self::SUM,
			self::AVG,
			self::MIN,
			self::MAX,
			self::COUNT
		];
	}
}