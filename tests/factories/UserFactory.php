<?php

use Logstats\Domain\User\User;

class UserFactory {
	private static $id = 1;

	public static function randomUser() {
		$faker = Faker\Factory::create();
		$user = new User(
			$faker->name,
			$faker->email,
			bcrypt(str_random(10)),
			str_random(10)
		);
		$user->setId(self::$id++);
		return $user;
	}
}