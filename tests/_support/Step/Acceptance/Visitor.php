<?php  namespace Step\Acceptance;

use Page\LoginPage;

class Visitor extends \AcceptanceTester
{
	public static $name = 'visitor_user';
	public static $password = 'tester';

	public function loginAsVisitor()
	{
		$I = $this;
		$loginPage = new LoginPage($I);
		$loginPage->login(self::$name, self::$password);
	}

}