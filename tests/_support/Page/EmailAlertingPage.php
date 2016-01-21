<?php
namespace Page;

class EmailAlertingPage
{
    // include url of current page
    public static $URL = 'alerting';

	public static $emailField = 'input[name="email"]';

	public static $levelSelect = 'select[name="level"]';

	public static $submitButton = 'input[value="Save"]';

	private $tester;

	public function __construct(\AcceptanceTester $tester) {
		$this->tester = $tester;
	}

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

	public function newAlerting($email, $level) {
		$I = $this->tester;
		$I->fillField(self::$emailField, $email);
		$I->selectOption(self::$levelSelect, $level);
		$I->click(self::$submitButton);
	}

	public function deleteAlerting($alertingId) {
		$I = $this->tester;
		$I->submitForm('form[action$="alerting/'.$alertingId.'"]', []);
	}
}
