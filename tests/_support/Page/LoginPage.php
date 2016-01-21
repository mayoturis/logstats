<?php
namespace Page;

class LoginPage
{
    // include url of current page
    public static $URL = 'auth/login';

	public static $title = 'Login';

	public static $usernameField = 'input[name="name"]';

	public static $passwordField = 'input[name="password"]';

	public static $loginButton = 'input[type="submit"]';

	public static $registerLink = '(Register)';

	private $tester;
	/**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

	public function __construct(\AcceptanceTester $tester) {
		$this->tester = $tester;
	}

	public function login($name, $password) {
		$I = $this->tester;
		$I->amOnPage(self::$URL);
		$I->fillField(self::$usernameField, $name);
		$I->fillField(self::$passwordField, $password);
		$I->click(self::$loginButton);
	}

	public function clickRegister() {
		$I = $this->tester;
		$I->amOnPage(self::$URL);
		$I->click(self::$registerLink);
	}
}
