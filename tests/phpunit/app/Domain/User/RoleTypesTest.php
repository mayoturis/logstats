<?php

use Logstats\Domain\User\RoleTypes;

class RoleTypesTest extends TestCase {
	public function test_super_roles_for_visitor_can_be_get() {
		$visitorSuperRoles = [RoleTypes::ADMIN, RoleTypes::DATAMANAGER, RoleTypes::VISITOR];

		$this->assertEquals($visitorSuperRoles, RoleTypes::allSuperRoles(RoleTypes::VISITOR));
	}

	public function test_super_roles_for_admin_can_be_get() {
		$adminSuperRoles = [RoleTypes::ADMIN];

		$this->assertEquals($adminSuperRoles, RoleTypes::allSuperRoles(RoleTypes::ADMIN));
	}

	public function test_empty_array_is_returned_in_super_roles_for_invalid_role() {
		$this->assertEquals([], RoleTypes::allSuperRoles('asdfasdf'));
	}

	public function test_sub_roles_for_visitor_can_be_get() {
		$visitorSubRoles = [RoleTypes::VISITOR];

		$this->assertEquals($visitorSubRoles, RoleTypes::allSubRoles(RoleTypes::VISITOR));
	}

	public function test_sub_roles_for_admin_can_be_get() {
		$adminSubRoles = [RoleTypes::ADMIN, RoleTypes::DATAMANAGER, RoleTypes::VISITOR];

		$this->assertEquals($adminSubRoles, RoleTypes::allSubRoles(RoleTypes::ADMIN));
	}

	public function test_empty_array_is_returned_in_sub_roles_for_invalid_role() {
		$this->assertEquals([], RoleTypes::allSubRoles('dadsfa'));
	}

	public function test_all_roles_can_be_get() {
		$allRoles = [
			RoleTypes::VISITOR,
			RoleTypes::DATAMANAGER,
			RoleTypes::ADMIN,
		];

		$this->assertEquals($allRoles, RoleTypes::allRoles());
	}
}