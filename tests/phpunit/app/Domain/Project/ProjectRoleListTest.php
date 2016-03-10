<?php

use Logstats\Domain\Project\ProjectRoleList;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\RoleTypes;

class ProjectRoleListTest extends TestCase {
	public function test_setRole_adds_new_user_with_role_if_he_does_not_exist() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user, new Role(RoleTypes::ADMIN));

		$this->assertTrue($projectRoleList->isAdmin($user));
	}

	public function test_setRole_can_set_admin_role() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user, new Role(RoleTypes::ADMIN));

		$this->assertTrue($projectRoleList->isAdmin($user));
		$this->assertTrue($projectRoleList->isDataManager($user));
		$this->assertTrue($projectRoleList->isVisitor($user));
	}

	public function test_setRole_can_set_datamanager_role() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user, new Role(RoleTypes::DATAMANAGER));

		$this->assertFalse($projectRoleList->isAdmin($user));
		$this->assertTrue($projectRoleList->isDataManager($user));
		$this->assertTrue($projectRoleList->isVisitor($user));
	}

	public function test_setRole_can_set_visitor_role() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user, new Role(RoleTypes::VISITOR));

		$this->assertFalse($projectRoleList->isAdmin($user));
		$this->assertFalse($projectRoleList->isDataManager($user));
		$this->assertTrue($projectRoleList->isVisitor($user));
	}

	public function test_setRole_can_change_role() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user, new Role(RoleTypes::ADMIN));
		$projectRoleList->setRole($user, new Role(RoleTypes::VISITOR));

		$this->assertFalse($projectRoleList->isAdmin($user));
		$this->assertFalse($projectRoleList->isDataManager($user));
		$this->assertTrue($projectRoleList->isVisitor($user));
	}

	public function test_getRoleForUser_returns_correct_role() {
		$projectRoleList = new ProjectRoleList();
		$user = UserFactory::randomUser();

		$projectRoleList->setRole($user, new Role(RoleTypes::VISITOR));
		$role = $projectRoleList->getRoleForUser($user);

		$this->assertFalse($role->isRole(RoleTypes::ADMIN));
		$this->assertFalse($role->isRole(RoleTypes::DATAMANAGER));
		$this->assertTrue($role->isRole(RoleTypes::VISITOR));
	}

	public function test_more_users_can_be_hold() {
		$user1 = UserFactory::randomUser();
		$user2 = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();
		$projectRoleList->setRole($user1, new Role(RoleTypes::ADMIN));
		$projectRoleList->setRole($user2, new Role(RoleTypes::VISITOR));

		$this->assertTrue($projectRoleList->isAdmin($user1));
		$this->assertFalse($projectRoleList->isAdmin($user2));
		$this->assertTrue($projectRoleList->isVisitor($user1));
		$this->assertTrue($projectRoleList->isVisitor($user2));
	}

	public function test_getUsers_returns_all_users() {
		$user1 = UserFactory::randomUser();
		$user2 = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();

		$projectRoleList->setRole($user1, new Role(RoleTypes::ADMIN));
		$projectRoleList->setRole($user2, new Role(RoleTypes::VISITOR));

		$users = $projectRoleList->getUsers();

		$this->assertTrue(in_array($user1, $users));
		$this->assertTrue(in_array($user2, $users));
	}

	public function test_false_is_returned_when_user_is_not_in_list() {
		$user = UserFactory::randomUser();
		$projectRoleList = new ProjectRoleList();

		$this->assertFalse($projectRoleList->isAdmin($user));
		$this->assertFalse($projectRoleList->isDataManager($user));
		$this->assertFalse($projectRoleList->isVisitor($user));
	}
}