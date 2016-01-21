<?php


use Page\RegisterPage;

class RegisterCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

	public function test_i_can_register(AcceptanceTester $I) {
		$I->wantTo('Register');
		$registerPage = new RegisterPage($I);
		$registerPage->register('new_name', 'email@email.com', 'password', 'password');
		$I->see('new_name');
		$I->seeIamOnHomePage();
	}

	public function test_i_see_errors_when_i_register_with_existing_user(AcceptanceTester $I) {
		$I->wantTo('Register');
		$registerPage = new RegisterPage($I);
		$registerPage->register('admin', 'email@email.com', 'password', 'password');
		$I->seeIAmOnRegisterPage();
		$I->see('taken');
		$I->seeInField(RegisterPage::$nameField, 'admin');
		$I->seeInField(RegisterPage::$emailField, 'email@email.com');
		$I->seeInField(RegisterPage::$passwordFiled, '');
	}

	public function test_i_see_errors_when_i_register_with_different_repeat_password(AcceptanceTester $I) {
		$I->wantTo('Register');
		$registerPage = new RegisterPage($I);
		$registerPage->register('admin', 'email@email.com', 'password', 'another_password');
		$I->seeIAmOnRegisterPage();
		$I->see('match');
	}

	public function test_i_see_errors_when_i_register_with_long_password(AcceptanceTester $I) {
		$I->wantTo('Register');
		$registerPage = new RegisterPage($I);
		$registerPage->register('admin', 'email@email.com', str_repeat('a', 100), str_repeat('a', 100));
		$I->seeIAmOnRegisterPage();
	}
}
