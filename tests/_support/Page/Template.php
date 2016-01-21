<?php
namespace Page;

class Template
{

	public static $currentProjectHolder = '.sidebar .project-name';

	public static $loggedUserHodler = '.user-menu span';

	public static $logoutButton = 'a[href$="logout"]';

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


}
