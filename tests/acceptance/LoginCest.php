<?php


use Page\AllProjectsPage;
use Page\LoginPage;
use Page\RegisterPage;
use Page\Template;
use Step\Acceptance\Admin;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function test_i_can_login(AcceptanceTester $I)
    {
		$I->wantTo('Login');
		$loginPage = new LoginPage($I);
		$loginPage->login('admin','tester');
		$I->dontSeeIAmOnLoginPage();
		$I->seeIAmOnHomePage();
		$I->see('admin', Template::$loggedUserHodler);
    }

	public function test_i_see_reason_when_i_fill_invalid_credentials(AcceptanceTester $I) {
		$I->wantTo('Login');
		$loginPage = new LoginPage($I);
		$loginPage->login('wrong_name','wrong_password');
		$I->seeIAmOnLoginPage();
		$I->see('Invalid credentials');
		$I->seeInField(LoginPage::$usernameField, 'wrong_name');
		$I->seeInField(LoginPage::$passwordField, '');
	}

	public function test_i_can_go_on_register_page(AcceptanceTester $I) {
		$I->wantTo('Go on register page');
		$loginPage = new LoginPage($I);
		$loginPage->clickRegister();
		$I->seeIAmOnRegisterPage();
	}

	public function test_i_can_logout(Admin $I) {
		$I->wantTo('Logout');
		$I->loginAsAdmin();
		$I->click(Template::$logoutButton);
		$I->seeIAmOnLoginPage();
		$I->amOnPage(AllProjectsPage::$URL);
		$I->seeIAmOnLoginPage();
	}

	public function test_i_am_redirected_on_login_page_if_i_am_not_logged_in(AcceptanceTester $I) {
		$I->wantTo('Log in');
		$I->amOnPage(AllProjectsPage::$URL);
		$I->seeIAmOnLoginPage();
	}
}
