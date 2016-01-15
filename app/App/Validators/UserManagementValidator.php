<?php  namespace Logstats\App\Validators; 

use Logstats\Domain\User\RoleTypes;

class UserManagementValidator extends AbstractValidator {

	public function isValidUsersRolesRoot($data) {
		return $this->isValid($data, [
			'users' => 'required|array'
		]);
	}

	public function isValidUserRole($data) {
		$roles = RoleTypes::allRoles();
		return $this->isValid($data, [
			'role' => 'in:'.join(',', $roles),
		]);
	}
}