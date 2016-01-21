<?php


use Step\Acceptance\NoneUser;
use Step\Acceptance\Visitor;

class ErrorCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function test_i_can_see_404_error_page(AcceptanceTester $I) {
		// error reporting has to be turned off
		$I->amOnPage('invalid_page');
		$I->see('404');
		$I->see('page not found');
	}

	public function test_i_can_see_401_error_page(Visitor $I) {
		$I->loginAsVisitor();
		$I->amOnPage('user-management');
		$I->see('access denied');
	}
}
