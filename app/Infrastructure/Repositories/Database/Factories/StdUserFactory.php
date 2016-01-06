<?php  namespace Logstats\Infrastructure\Repositories\Database\Factories; 
use Logstats\Domain\User\Role;
use Logstats\Domain\User\User;

class StdUserFactory {

	/**
	 * @param mixed $stdClass Make object
	 * @return User
	 */
	public function makeFromStd($stdClass) {
		$user = new User(
			$stdClass->name,
			$stdClass->password,
			$stdClass->email,
			$stdClass->remember_token,
			$this->getRoleOrNull($stdClass->role)
		);
		$user->setId($stdClass->id);
		return $user;
	}

	public function makeFromStdArray($stdArray) {
		$users = [];
		foreach ($stdArray as $stdObject) {
			$users[] = $this->makeFromStd($stdObject);
		}

		return $users;
	}

	private function getRoleOrNull($role) {
		if ($role === null) {
			return null;
		}
		return new Role($role);
	}

}