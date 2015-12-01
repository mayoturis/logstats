<?php  namespace Logstats\Services\Auth; 

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Logstats\Repositories\Contracts\UserRepository;

class EntityUserProvider implements UserProvider {

	/**
	 *
	 */
	private $repository;
	/**
	 *
	 */
	private $hasher;

	public function __construct(UserRepository $repository, Hasher $hasher) {
		$this->repository = $repository;
		$this->hasher = $hasher;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed $identifier
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($identifier) {
		return $this->repository->findById($identifier);
	}

	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 *
	 * @param  mixed $identifier
	 * @param  string $token
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken($identifier, $token) {
		return $this->repository->findBy([
			'id' => $identifier,
			'remember_token' => $token
		])->first();
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  string $token
	 * @return void
	 */
	public function updateRememberToken(Authenticatable $user, $token) {
		$userEntity = $this->repository->findById($user->getAuthIdentifier());
		$userEntity->setRememberToken($token);
		$this->repository->save($userEntity);
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array $credentials
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials(array $credentials) {
		unset($credentials['password']);
		return $this->repository->findBy($credentials)->first();;
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  array $credentials
	 * @return bool
	 */
	public function validateCredentials(Authenticatable $user, array $credentials) {
		$plain = $credentials['password'];

		return $this->hasher->check($plain, $user->getAuthPassword());
	}
}