<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Lib\ModuleContainer;
use Codeception\Module\PhpBrowser;
use Page\CreateProject;
use Page\CreateProjectPage;
use Page\EmailAlertingPage;
use Page\LoginPage;
use Page\LogPage;
use Page\ProjectManagementPage;
use Page\RegisterPage;
use Page\SegmentationPage;
use Page\UserManagementPage;

class Acceptance extends \Codeception\Module
{

	/**
	 * @var PhpBrowser
	 */
	private $tester;

	public function __construct(ModuleContainer $moduleContainer, $config = null) {
		parent::__construct($moduleContainer, $config);
		$this->tester = $this->getModule('PhpBrowser');
	}

	public function seeIAmOnHomePage() {
		$this->seeIAmOnAllProjectsPage();
	}

	public function dontSeeIAmOnHomePage() {
		$this->dontSeeIAmOnAllProjectsPage();
	}

	public function seeIAmOnAllProjectsPage() {
		$I = $this->tester;
		$I->seeInTitle('Projects');
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnAllProjectsPage() {
		$I = $this->tester;
		$I->dontSeeInTitle('Projects');
	}

	public function seeIAmOnLoginPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(LoginPage::$URL);
		$I->seeInTitle(LoginPage::$title);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnLoginPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(LoginPage::$URL);
		$I->dontSeeInTitle(LoginPage::$title);
	}

	public function seeIAmOnRegisterPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(RegisterPage::$URL);
		$I->seeInTitle(RegisterPage::$title);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnRegisterPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(RegisterPage::$URL);
		$I->dontSeeInTitle(RegisterPage::$title);
	}

	public function seeIAmOnCreateProjectPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(CreateProjectPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnCreateProjectPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(CreateProjectPage::$URL);
	}

	public function seeIAmOnLogPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(LogPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnLogPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(LogPage::$URL);
	}

	public function seeIAmOnSegmentationPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(SegmentationPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnSegmentationPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(SegmentationPage::$URL);
	}

	public function seeIAmOnEmailAlertingPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(EmailAlertingPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnEmailAlertingPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(EmailAlertingPage::$URL);
	}

	public function seeIAmOnProjectManagementPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(ProjectManagementPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnProjectManagementPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(ProjectManagementPage::$URL);
	}

	public function seeIAmOnUserManagementPage() {
		$I = $this->tester;
		$I->seeInCurrentUrl(UserManagementPage::$URL);
		$I->seeResponseCodeIs(200);
	}

	public function dontSeeIAmOnUserManagementPage() {
		$I = $this->tester;
		$I->dontSeeInCurrentUrl(UserManagementPage::$URL);
	}
}
