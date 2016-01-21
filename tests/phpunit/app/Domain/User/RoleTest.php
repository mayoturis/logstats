<?php

use Logstats\Domain\User\Role;
use Logstats\Domain\User\RoleTypes;

class RoleTest extends  TestCase {

	public function test_admin_role_returns_correct_sub_roles() {
		$role = new Role(RoleTypes::ADMIN);

		$subRoles = $role->allSubRoles();

		$this->assertTrue(in_array(RoleTypes::VISITOR, $subRoles));
		$this->assertTrue(in_array(RoleTypes::DATAMANAGER, $subRoles));
		$this->assertTrue(in_array(RoleTypes::ADMIN, $subRoles));
	}

	public function test_datamanager_role_returns_correct_sub_roles() {
		$role = new Role(RoleTypes::DATAMANAGER);

		$subRoles = $role->allSubRoles();

		$this->assertTrue(in_array(RoleTypes::VISITOR, $subRoles));
		$this->assertTrue(in_array(RoleTypes::DATAMANAGER, $subRoles));
		$this->assertFalse(in_array(RoleTypes::ADMIN, $subRoles));
	}

	public function test_visitor_role_returns_correct_sub_roles() {
		$role = new Role(RoleTypes::VISITOR);

		$subRoles = $role->allSubRoles();

		$this->assertTrue(in_array(RoleTypes::VISITOR, $subRoles));
		$this->assertFalse(in_array(RoleTypes::DATAMANAGER, $subRoles));
		$this->assertFalse(in_array(RoleTypes::ADMIN, $subRoles));
	}

	public function test_isRole_on_admin_returns_true_for_correct_roles() {
		$role = new Role(RoleTypes::ADMIN);

		$this->assertTrue($role->isRole(RoleTypes::VISITOR));
		$this->assertTrue($role->isRole(RoleTypes::DATAMANAGER));
		$this->assertTrue($role->isRole(RoleTypes::ADMIN));
	}

	public function test_isRole_on_datamanager_returns_true_for_correct_roles() {
		$role = new Role(RoleTypes::DATAMANAGER);

		$this->assertTrue($role->isRole(RoleTypes::VISITOR));
		$this->assertTrue($role->isRole(RoleTypes::DATAMANAGER));
		$this->assertFalse($role->isRole(RoleTypes::ADMIN));
	}

	public function test_isRole_on_visitor_returns_true_for_correct_roles() {
		$role = new Role(RoleTypes::VISITOR);

		$this->assertTrue($role->isRole(RoleTypes::VISITOR));
		$this->assertFalse($role->isRole(RoleTypes::DATAMANAGER));
		$this->assertFalse($role->isRole(RoleTypes::ADMIN));
	}

}