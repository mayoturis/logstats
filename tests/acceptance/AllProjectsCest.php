<?php


use Page\AllProjectsPage;
use Page\Template;
use Step\Acceptance\Admin;
use Step\Acceptance\NoneUser;

class AllProjectsCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_am_redirected_to_login_page_if_i_am_not_logged_in(AcceptanceTester $I) {
		$I->wantTo('See projects but I am not logged in');
		$I->amOnPage(AllProjectsPage::$URL);
		$I->seeIAmOnLoginPage();
	}

	public function test_i_can_see_projects(Admin $I) {
		$I->wantTo('See projects');
		$I->loginAsAdmin();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->see('project1');
		$I->see('writeProject1Token');
		$I->see('readProject1Token');
		$I->see('Delete project');
	}

	public function test_i_dont_see_project_which_i_cant(NoneUser $I) {
		$I->wantTo('See projects');
		$I->loginAsNoneUser();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->dontSee('project1');
	}

	public function test_i_can_go_on_new_project_page(Admin $I) {
		$I->wantTo('Go on new project page');
		$I->loginAsAdmin();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->click(AllProjectsPage::$newProjectButton);
		$I->seeIAmOnCreateProjectPage();
	}

	public function test_i_can_can_delete_project(Admin $I) {
		$I->wantTo('Delete project1');
		$I->loginAsAdmin();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->submitForm(AllProjectsPage::$project1deleteForm, []);
		$I->seeIAmOnAllProjectsPage();
		$I->see('deleted');
		$I->dontSee('project1');
	}

	public function test_i_can_choose_project(Admin $I) {
		$I->wantTo('Choose project1');
		$I->loginAsAdmin();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->click(AllProjectsPage::$project1Link);
		$I->seeIAmOnLogPage();
		$I->see('project1', Template::$currentProjectHolder);
	}
}
