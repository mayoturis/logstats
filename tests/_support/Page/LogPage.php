<?php
namespace Page;

use Step\Acceptance\Admin;

class LogPage
{
    // include url of current page
    public static $URL = 'log';

	private static $ajaxRecordsUrl = 'record/ajax-show?project-id=';

	private $tester;

	public function __construct(Admin $tester) {

		$this->tester = $tester;
	}

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

	public static function ajaxRecordsUrl($projectId) {
		return self::$ajaxRecordsUrl . $projectId;
	}

	public function seeInRecords($projectId, $level, $message, $context) {
		$I = $this->tester;
		$I->chooseProject($projectId);
		$I->amOnPage(self::ajaxRecordsUrl($projectId));
		$I->see($level . ' - ' . $message . ' - '.json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public function dontSeeInRecords($projectId, $level, $message, $context) {
		$I = $this->tester;
		$I->chooseProject($projectId);
		$I->amOnPage(self::ajaxRecordsUrl($projectId));
		$I->dontSee($level . ' - ' . $message . ' - '.json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

}
