<?php
namespace Page;

class RegisterPage
{

	private $tester;

	public function __construct(\AcceptanceTester $tester) {
		$this->tester = $tester;
	}

    // include url of current page
    public static $URL = 'auth/register';

	public static $title = 'Register';

	public static $nameField = 'input[name="name"]';

	public static $emailField = 'input[name="email"]';

	public static $passwordFiled = 'input[name="password"]';

	public static $passwordRepeatField = 'input[name="password_confirmation"]';

	public static $registerButton = 'input[type="submit"]';

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

	public function register($name, $email, $password, $password2) {
		$I = $this->tester;
		$I->amOnPage('auth/register');
		$I->fillField(self::$nameField, $name);
		$I->fillField(self::$emailField, $email);
		$I->fillField(self::$passwordFiled, $password);
		$I->fillField(self::$passwordRepeatField, $password2);
		$I->click(self::$registerButton);
	}
}
