<?php

use Logstats\Domain\User\Role;
use Logstats\Domain\User\User;
use Logstats\Infrastructure\Repositories\Database\DbUserRepository;
use Logstats\Infrastructure\Repositories\Database\Factories\StdUserFactory;

class DbUserRepositoryTest extends DatabaseTestCase {

	/**
	 * @var DbUserRepository
	 */
	private $dbUserRepository;

	public function setUp() {
		parent::setUp();

		$this->dbUserRepository = $this->getDbUserRepository();
	}

	public function test_user_can_be_found_by_id() {
		$user = $this->dbUserRepository->findById(1);

		$this->assertEquals($user->getId(), 1);
		$this->assertEquals('adminName', $user->getName());
		$this->assertEquals('adminEmail', $user->getEmail());
		$this->assertNull($user->getRememberToken());
		$this->assertEquals('admin', $user->getRole());
	}

	public function test_users_can_be_found_by_conditions() {
		$users = $this->dbUserRepository->findBy(['remember_token' => 'token']);
		$this->assertEquals(2, count($users));
	}

	public function test_all_users_can_be_get() {
		$this->assertEquals(4, count($this->dbUserRepository->getAll()));
	}

	public function test_save_inserts_user_if_one_not_exists() {
		$user = new User('name', 'password', 'email', null, new Role('admin'));
		$this->dbUserRepository->save($user);

		$savedUser = $this->dbUserRepository->findById($user->getId());

		$this->assertNotEmpty($user->getId());
		$this->assertEquals('name', $savedUser->getName());
		$this->assertEquals('password', $savedUser->getPassword());
		$this->assertEquals('email', $savedUser->getEmail());
		$this->assertNull($user->getRememberToken());
		$this->assertEquals('admin', $user->getRole()->getName());

		$this->assertEquals(5, count($this->dbUserRepository->getAll()));
	}

	public function test_save_updates_user_if_one_exists() {
		$user = $this->dbUserRepository->findById(1);
		$user->setName('newName');
		$user->setRole(null);
		$this->dbUserRepository->save($user);

		$updatedUser = $this->dbUserRepository->findById(1);

		$this->assertEquals('newName', $updatedUser->getName());
		$this->assertNull($updatedUser->getRole());
		$this->assertEquals($user->getEmail(), $updatedUser->getEmail());
	}


	/**
	 * @return DbUserRepository
	 */
	public function getDbUserRepository() {
		return new DbUserRepository(new StdUserFactory());
	}

	public function test_user_can_be_deleted() {
		$user = $this->dbUserRepository->findById(2);

		$this->dbUserRepository->delete($user);

		$this->assertNull($this->dbUserRepository->findById(2));
		$this->assertEquals(3, count($this->dbUserRepository->getAll()));
	}

}