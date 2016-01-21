<?php
namespace Page;

use Step\Acceptance\Admin;

class UserManagementPage
{
    // include url of current page
    public static $URL = 'user-management';

	public static $allProjectsForm = 'form[action$="user-management-all"]';

	public static $firstProjectForm = 'form[action$="user-management-project"]:nth-of-type(1)';

	private $tester;

	public function __construct(Admin $tester) {
		$this->tester = $tester;
	}

	public static function option($userId) {
		return 'input[name="users['.$userId.'][role]"]';
	}

	public static function allProjectsOption($userId) {
		return self::$allProjectsForm . ' ' . self::option($userId);
	}

	public function selectAllProjectsOption($userId, $value) {
		$I = $this->tester;
		$I->selectOption(self::allProjectsOption($userId), $value);
	}

	public function submitAllProjectsForm() {
		$I = $this->tester;
		$I->click(self::$allProjectsForm . ' input[type="submit"]');
	}

	public function deleteUser($userId) {
		$I = $this->tester;
		$I->submitForm('form#delete-'.$userId, []);
	}
}
