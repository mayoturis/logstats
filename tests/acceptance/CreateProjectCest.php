<?php


use Page\CreateProjectPage;
use Step\Acceptance\Admin;

class CreateProjectCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_create_new_project(Admin $I) {
		$I->wantTo('Create new project');
		$I->loginAsAdmin();
		$I->amOnPage(CreateProjectPage::$URL);
		$page = new CreateProjectPage($I);
		$projectName = 'new_project';
		$page->createProject($projectName);
		$I->seeIAmOnAllProjectsPage();
		$I->see($projectName);
		$I->see('successfully created');
	}

	public function test_i_see_errors_when_i_create_project_with_existing_name(Admin $I) {
		$I->wantTo('Create new project');
		$I->loginAsAdmin();
		$I->amOnPage(CreateProjectPage::$URL);
		$page = new CreateProjectPage($I);
		$page->createProject('project1');
		$I->dontSeeIAmOnAllProjectsPage();
		$I->seeIAmOnCreateProjectPage();
		$I->see('taken');
	}
}
