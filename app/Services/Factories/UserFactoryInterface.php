<?php  namespace Logstats\Services\Factories;

interface UserFactoryInterface {
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
	public function make($id = null, $name = null, $password = null, $email = null, $remember_token = null);
}