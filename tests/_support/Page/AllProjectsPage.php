<?php
namespace Page;

class AllProjectsPage
{
    // include url of current page
    public static $URL = '/';

	public static $newProjectButton = 'New project';

	public static $project1deleteForm = 'form[action$="projects/1"]';

	public static $project1Link = 'a[href$="projects/1"]';

	public static function projectLink($projectId) {
		return 'a[href$="projects/'.$projectId.'"]';
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
}
