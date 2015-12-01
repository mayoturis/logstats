<?php

namespace Logstats\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use InvalidArgumentException;
use Logstats\Http\Requests;
use Logstats\Services\Database\DatabaseConfigServiceInterface;
use Logstats\Services\Database\DatabaseCreator;
use Logstats\Services\Database\TableCreator;
use Logstats\Services\Entities\ProjectServiceInterface;
use Logstats\Services\Entities\UserServiceInterface;
use Logstats\Services\Installation\InstallationServiceInterface;
use Logstats\Services\Installation\InstallationSteps;
use Logstats\Services\Installation\StepCollection;
use Logstats\Services\Installation\Steps;
use Logstats\Services\Validators\ProjectValidator;
use Logstats\Services\Validators\TimeZoneValidator;
use Logstats\Services\Validators\UserValidator;
use Logstats\Services\Validators\ValidationException;
use Logstats\ValueObjects\Role;
use Logstats\ValueObjects\RoleTypes;
use Mayoturis\Properties\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InstallationController extends Controller
{

	/**
	 *
	 */
	private $steps;
	/**
	 *
	 */
	private $databaseConfig;
	/**
	 *
	 */
	private $tableCreator;
	/**
	 *
	 */
	private $dbCreator;
	/**
	 * Logstats\Services\Validators\UserValidator
	 */
	private $userValidator;
	/**
	 *
	 */
	private $userService;
	/**
	 *
	 */
	private $projectService;
	/**
	 *
	 */
	private $projectValidator;
	/**
	 *
	 */
	private $config;
	/**
	 *
	 */
	private $timeZoneValidator;
	/**
	 *
	 */
	private $auth;
	/**
	 *
	 */
	private $installationService;

	/**
	 * @param StepCollection $steps Steps of the installation
	 * @param DatabaseConfigServiceInterface $databaseConfig
	 * @param TableCreator $tableCreator
	 * @param DatabaseCreator $dbCreator
	 * @param UserValidator $userValidator
	 * @param UserServiceInterface $userService
	 * @param ProjectServiceInterface $projectService
	 * @param ProjectValidator $projectValidator
	 * @param RepositoryInterface $config
	 * @param TimeZoneValidator $timeZoneValidator
	 * @param InstallationServiceInterface $installationService
	 */
	public function __construct(StepCollection $steps,
								DatabaseConfigServiceInterface $databaseConfig,
								TableCreator $tableCreator,
								DatabaseCreator $dbCreator,
								UserValidator $userValidator,
								UserServiceInterface $userService,
								ProjectServiceInterface $projectService,
								ProjectValidator $projectValidator,
								RepositoryInterface $config,
								TimeZoneValidator $timeZoneValidator,
								Guard $auth,
								InstallationServiceInterface $installationService) {
		$this->steps = $steps;
		$this->databaseConfig = $databaseConfig;
		$this->tableCreator = $tableCreator;
		$this->dbCreator = $dbCreator;
		$this->userValidator = $userValidator;
		$this->userService = $userService;
		$this->projectService = $projectService;
		$this->projectValidator = $projectValidator;
		$this->config = $config;
		$this->timeZoneValidator = $timeZoneValidator;
		$this->auth = $auth;
		$this->installationService = $installationService;
	}

	public function index(Request $request, $step = 1) {
		try {
			$fullStep = $this->steps->getStep($step);
		} catch(InvalidArgumentException $ex) {
			throw new NotFoundHttpException(null, $ex);
		}

		$method = $fullStep['short'];

		return $this->$method($request);
	}

    public function welcome()
    {
		$this->basicConfiguration();
		$this->installationService->setNextInstallationStep(Steps::WELCOME);
		return view('installation.welcome');
    }

	public function basicConfiguration() {
		$this->installationService->setRandomAppKey();
	}

	public function databaseSetup(Request $request) {
		if ($request->isMethod('get')) {
			return $this->getDatabaseSetup($request);
		} else {
			return $this->postDatabaseSetup($request);
		}
	}

	public function getDatabaseSetup(Request $request) {
		return view('installation.databasesetup');
	}

	public function postDatabaseSetup(Request $request) {
		try {
			$this->databaseConfig->saveConfiguration($request->all());
		} catch(ValidationException $ex) {
			return redirect()
				->route('installation', ['step' => $this->steps->getKeyByShort(Steps::DATABASE_SETUP)])
				->withInput()
				->withErrors($ex->getErrors(), 'databaseSetup');
		}

		$this->installationService->setNextInstallationStep(Steps::DATABASE_SETUP);

		return redirect()
			->route('installation', ['step' => $this->steps->nextKeyForShort(Steps::DATABASE_SETUP)]);
	}

	public function createTables() {
		$errorMessage = null;

		try {
			//$this->dbCreator->createDatabaseIfNotExists();
			$this->tableCreator->migrateDatabase();
			$this->installationService->setNextInstallationStep(Steps::CREATE_TABLES);
		} catch(\Exception $ex) {
			$errorMessage = $ex->getMessage();
			$this->installationService->setInstallationStep(Steps::DATABASE_SETUP);
		}

		return view('installation.createtables')->with('errorMessage', $errorMessage);
	}

	public function generalSetup(Request $request) {
		if ($request->isMethod('get')) {
			return $this->getGeneralSetup($request);
		} else {
			return $this->postGeneralSetup($request);
		}
	}

	public function getGeneralSetup(Request $request) {
		return view('installation.generalsetup');
	}

	public function postGeneralSetup(Request $request) {
		// validate user
		if (! $this->userValidator->isValidForCreate($request->all())) {
			return redirect()
				->route('installation', ['step' => $this->steps->getKeyByShort(Steps::GENERAL_SETUP)])
				->withInput()
				->withErrors($this->userValidator->getErrors(), 'generalSetupUser');
		}

		// validate timezone
		if (! $this->timeZoneValidator->isValidTimezone($request->all())) {
			return redirect()
				->route('installation', ['step' => $this->steps->getKeyByShort(Steps::GENERAL_SETUP)])
				->withInput()
				->withErrors($this->projectValidator->getErrors(), 'generalSetupTimezone');
		}


		// create admin
		$user = $this->userService->createUser(
			$request->get('name'),
			$request->get('password'),
			$request->get('email'));
		$this->userService->addRoleToUser($user, new Role(RoleTypes::ADMIN));
		$this->auth->login($user);


		// create project if name was provided
		if ($this->projectValidator->isValidForCreate(["name" => $request->get('project_name')])) {
			$this->projectService->createProject($request->get('project_name'), $user);
		}

		// set timezone
		$this->config->set('TIMEZONE', $request->get('timezone'));

		$this->installationService->setNextInstallationStep(Steps::GENERAL_SETUP);

		return redirect()
			->route('installation', ['step' => $this->steps->nextKeyForShort(Steps::GENERAL_SETUP)]);
	}

	public function congratulations(Request $request) {
		$this->installationService->setInstallationStep(Steps::COMPLETE);
		return view('installation.congratulations');
	}
}
