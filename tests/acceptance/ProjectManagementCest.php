<?php


use Page\LogPage;
use Page\ProjectManagementPage;
use Page\SegmentationPage;
use Step\Acceptance\Admin;

class ProjectManagementCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_delete_all_records(Admin $I) {
		$I->wantTo('Delete all records');
		$I->loginAsAdmin();
		$I->chooseProject(2);
		$page = new ProjectManagementPage($I);
		$page->deleteAllProjects();

		$I->seeIAmOnProjectManagementPage();
		$I->see('deleted');

		$I->amOnPage(LogPage::ajaxRecordsUrl(2));
		$I->dontSee('terible');
		$I->dontSee('purchase');
		$I->dontSee('visit');
	}
}
