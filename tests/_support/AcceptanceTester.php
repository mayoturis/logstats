<?php
use Page\AllProjectsPage;


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

	public function chooseProject($projectId) {
		$I = $this;
		$I->amOnPage(AllProjectsPage::$URL);
		$I->click(AllProjectsPage::projectLink($projectId));
	}


   /**
    * Define custom actions here
    */


}
