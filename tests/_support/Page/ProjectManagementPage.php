<?php
namespace Page;

use Step\Acceptance\Admin;

class ProjectManagementPage
{
    // include url of current page
    public static $URL = 'project-management';

	private $tester;

	public function __construct(Admin $tester) {
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

	public function deleteAllProjects() {
		$I = $this->tester;
		$I->amOnPage(self::$URL);
		$I->submitForm('#delete-logs',[]);
	}
}
