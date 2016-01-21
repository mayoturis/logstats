<?php
namespace Step\Acceptance;

use Page\LoginPage;

class Admin extends \AcceptanceTester
{
	public static $name = 'admin';
	public static $password = 'tester';

    public function loginAsAdmin()
    {
        $I = $this;
		$loginPage = new LoginPage($I);
		$loginPage->login(self::$name, self::$password);
    }

}