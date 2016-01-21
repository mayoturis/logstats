<?php
namespace Step\Acceptance;

use Page\LoginPage;

class NoneUser extends \AcceptanceTester
{

	public static $name = 'none';
	public static $password = 'tester';

	public function loginAsNoneUser()
    {
		$I = $this;
		$loginPage = new LoginPage($I);
		$loginPage->login(self::$name, self::$password);
    }

}