<?php
namespace Page;

class CreateProjectPage
{
    // include url of current page
    public static $URL = 'projects/create';

	public static $name = 'input[name="name"]';

	public static $submitButton = 'input[type="submit"]';

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

	public function createProject($name) {
		$I = $this->tester;
		$I->fillField(self::$name, $name);
		$I->click(self::$submitButton);
	}

}
