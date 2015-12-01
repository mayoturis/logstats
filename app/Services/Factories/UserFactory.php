<?php  namespace Logstats\Services\Factories;


use Logstats\Entities\User;

class UserFactory implements StdFactory, ArrayFactory, UserFactoryInterface {

	/**
	 * @param mixed $stdClass Make object
	 * @return \Logstats\Entities\User
	 */
	public function makeFromStd($stdClass) {
		$user = new User(
			$stdClass->name,
			$stdClass->email,
			$stdClass->password,
			$stdClass->remember_token
		);

		$user->setId($stdClass->id);

		return $user;
	}

	/**
	 * Create entity from array
	 *
	 * @param array $data
	 * @return \Logstats\Entities\User
	 */
	public function makeFromArray($data) {
		$user = new User(
			valueOrNull($data, 'name'),
			valueOrNull($data, 'email'),
			valueOrNull($data, 'password'),
			valueOrNull($data, 'rememeber_token')
		);

		$user->setId(valueOrNull($data, 'id'));

		return $user;
	}

	/**
	 * Create User
	 *
	 * @param string|null $id
	 * @param string|null $name
	 * @param string|null $password
	 * @param string|null $email
	 * @param string|null $remember_token
	 * @return \Logstats\Entities\User
	 */
	public function make($id = null, $name = null, $password = null, $email = null, $remember_token = null) {
		$user = new User($name, $password, $email, $remember_token);

		$user->setId($id);

		return $user;
	}
}