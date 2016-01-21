<?php
namespace Page;

class SegmentationPage
{
    // include url of current page
    public static $URL = 'segmentation';

	private static $showMessagesUrl = 'record/ajax-messages';

	private static $showPropertiesUrl = 'record/ajax-property-names?message-id=';

	public static function showMessagesUrl($projectId, $level = null) {
		$url = self::$showMessagesUrl . '?project-id='.$projectId;
		if ($level) {
			$url .= '&level='.$level;
		}
		return $url;
	}

	public static function showPropertiesUrl($messageId) {
		return self::$showPropertiesUrl . $messageId;
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
