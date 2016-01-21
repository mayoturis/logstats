<?php


use Page\SegmentationPage;
use Step\Acceptance\Admin;

class SegmentationCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

	public function test_i_can_visit_segmentation_page(Admin $I) {
		$I->loginAsAdmin();
		$I->chooseProject(2);
		$I->amOnPage(SegmentationPage::$URL);
		$I->seeIAmOnSegmentationPage();
	}

    public function test_all_messages_can_be_get(Admin $I) {
		$I->wantTo('See all message for project');
		$I->loginAsAdmin();
		$I->amOnPage(SegmentationPage::showMessagesUrl(2));
		$I->see('"total_count":3');
		$I->see('terrible');
		$I->see('purchase');
		$I->see('visit');
	}

	public function test_messages_by_level_can_be_get(Admin $I) {
		$I->wantTo('See all message for project and level');
		$I->loginAsAdmin();
		$I->amOnPage(SegmentationPage::showMessagesUrl(2, 'info'));
		$I->see('"total_count":2');
		$I->dontSee('terrible');
		$I->see('purchase');
		$I->see('visit');
	}

	public function test_properties_for_message_can_be_get(Admin $I) {
		$I->wantTo('See property names for message');
		$I->loginAsAdmin();
		$I->amOnPage(SegmentationPage::showPropertiesUrl(2));
		$I->see("price");
		$I->see("user");
	}
}
