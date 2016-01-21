<?php


use Page\SettingsPage;
use Step\Acceptance\Admin;

class SettingsCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_see_settings(Admin $I) {
		$I->loginAsAdmin();
		$I->amOnPage(SettingsPage::$URL);
		$I->see('settings');
	}
}
