<?php  namespace Logstats\Domain\Record; 

use Psr\Log\LogLevel;

class Level extends LogLevel {
	public static function getAll() {
		return [
			self::EMERGENCY,
			self::ALERT,
			self::CRITICAL,
			self::ERROR,
			self::WARNING,
			self::NOTICE,
			self::INFO,
			self::DEBUG
		];
	}
}