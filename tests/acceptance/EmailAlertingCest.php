<?php


use Page\EmailAlertingPage;
use Step\Acceptance\Admin;

class EmailAlertingCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_add_new_alerting(Admin $I) {
		$I->wantTo('Create new alerting');
		$I->loginAsAdmin();
		$I->chooseProject(1);
		$I->amOnPage(EmailAlertingPage::$URL);
		$page = new EmailAlertingPage($I);
		$page->newAlerting('new@alerting.com', 'info');
		$I->seeIAmOnEmailAlertingPage();
		$I->see('saved');
		$I->see('new@alerting.com');
		$I->see('info');
	}

	public function test_i_can_delete_alerting(Admin $I) {
		$I->wantTo('Delete alerting');
		$I->loginAsAdmin();
		$I->chooseProject(1);
		$I->amOnPage(EmailAlertingPage::$URL);
		$page = new EmailAlertingPage($I);
		$page->deleteAlerting(1);

		$I->seeIAmOnEmailAlertingPage();
		$I->see('deleted');
		$I->dontSee('email@email.com');
		$I->see('email2@email.com');
	}
}
