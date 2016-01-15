<?php  namespace Logstats\Domain\User;

abstract class RoleTypes {
	const ADMIN = "admin";
	const DATAMANAGER = "datamanager";
	const VISITOR = "visitor";

	private static $allSubRoles = [
		self::ADMIN => [
			self::ADMIN,
			self::DATAMANAGER,
			self::VISITOR,
		],
		self::DATAMANAGER => [
			self::DATAMANAGER,
			self::VISITOR,
		],
		self::VISITOR => [
			self::VISITOR
		],
	];

	private static $allSuperRoles = [
		self::DATAMANAGER => [
			self::ADMIN,
			self::DATAMANAGER,
		],
		self::ADMIN => [
			self::ADMIN
		],
		self::VISITOR => [
			self::ADMIN,
			self::DATAMANAGER,
			self::VISITOR,
		],
	];

	public static function allSubRoles($role) {
		if (!array_key_exists($role, self::$allSubRoles)) {
			return [];
		}

		return self::$allSubRoles[$role];
	}

	public static function allSuperRoles($role) {
		if (!array_key_exists($role, self::$allSuperRoles)) {
			return [];
		}

		return self::$allSuperRoles[$role];
	}

	public static function allRoles() {
		return [
			self::VISITOR,
			self::DATAMANAGER,
			self::ADMIN,
		];
	}
}