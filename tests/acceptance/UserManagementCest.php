<?php


use Page\UserManagementPage;
use Step\Acceptance\Admin;

class UserManagementCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_see_roles(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(UserManagementPage::$URL);
		$I->see('admin');
		$I->see('visitor_user');
		$I->see('project1');
		$I->see('queryProject');
		$I->seeCheckboxIsChecked(UserManagementPage::allProjectsOption(1).'[value="admin"]');
		$I->seeCheckboxIsChecked(UserManagementPage::allProjectsOption(2).'[value="visitor"]');

	}

	public function test_i_can_change_user_role_to_admin(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(UserManagementPage::$URL);
		$page = new UserManagementPage($I);
		$page->selectAllProjectsOption(2, 'admin');
		$page->submitAllProjectsForm();
		$I->seeIAmOnUserManagementPage();
		$I->see('updated');
		$I->seeCheckboxIsChecked(UserManagementPage::allProjectsOption(2).'[value="admin"]');
	}

	public function test_i_can_change_user_role_to_none(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(UserManagementPage::$URL);
		$page = new UserManagementPage($I);
		$page->selectAllProjectsOption(2, '');
		$page->submitAllProjectsForm();
		$I->seeIAmOnUserManagementPage();
		$I->see('updated');
		$I->seeCheckboxIsChecked(UserManagementPage::allProjectsOption(2).'[value=""]');
	}

	public function test_i_can_change_user_role_in_project(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(UserManagementPage::$URL);
		$I->selectOption(UserManagementPage::$firstProjectForm . ' ' . UserManagementPage::option(2), 'admin');
		$I->click(UserManagementPage::$firstProjectForm . ' input[type="submit"]');
		$I->seeIAmOnUserManagementPage();
		$I->see('updated');
		$I->seeCheckboxIsChecked(UserManagementPage::$firstProjectForm . ' ' . UserManagementPage::option(2).'[value="admin"]');
	}

	public function test_i_can_delete_user(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(UserManagementPage::$URL);
		$page = new UserManagementPage($I);
		$page->deleteUser(2);
		$I->seeIAmOnUserManagementPage();
		$I->see('deleted');
		$I->dontSee('visitor_user');
	}


}
