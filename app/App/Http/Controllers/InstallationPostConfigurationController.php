<?php

namespace Logstats\App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

use InvalidArgumentException;
use Logstats\App\Http\Requests;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;
use Logstats\Domain\Services\Database\DatabaseConfigServiceInterface;
use Logstats\Domain\Services\Database\TableCreator;
use Logstats\Domain\Project\ProjectServiceInterface;
use Logstats\Domain\User\UserServiceInterface;
use Logstats\Domain\Services\Installation\InstallationServiceInterface;
use Logstats\Domain\Services\Installation\StepCollection;
use Logstats\Domain\Services\Installation\Steps;
use Logstats\App\Validators\ProjectValidator;
use Logstats\App\Validators\TimeZoneValidator;
use Logstats\App\Validators\UserValidator;
use Logstats\App\Validators\ValidationException;
use Logstats\Domain\User\Role;
use Logstats\Domain\User\RoleTypes;
use Mayoturis\Properties\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InstallationPostConfigurationController extends Controller
{

	private $steps;
	private $databaseConfig;
	private $tableCreator;
	private $userValidator;
	private $userService;
	private $projectService;
	private $projectValidator;
	private $config;
	private $timeZoneValidator;
	private $auth;
	private $installationService;
	private $currentProjectProvider;


	public function __construct(StepCollection $steps,
								DatabaseConfigServiceInterface $databaseConfig,
								TableCreator $tableCreator,
								UserValidator $userValidator,
								UserServiceInterface $userService,
								ProjectServiceInterface $projectService,
								ProjectValidator $projectValidator,
								RepositoryInterface $config,
								TimeZoneValidator $timeZoneValidator,
								Guard $auth,
								InstallationServiceInterface $installationService,
								CurrentProjectProviderInterface $currentProjectProvider) {
		$this->steps = $steps;
		$this->databaseConfig = $databaseConfig;
		$this->tableCreator = $tableCreator;
		$this->userValidator = $userValidator;
		$this->userService = $userService;
		$this->projectService = $projectService;
		$this->projectValidator = $projectValidator;
		$this->config = $config;
		$this->timeZoneValidator = $timeZoneValidator;
		$this->auth = $auth;
		$this->installationService = $installationService;
		$this->currentProjectProvider = $currentProjectProvider;
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
		$this->userService->setUserRole($user, new Role(RoleTypes::ADMIN));
		$this->auth->login($user);


		// create project if name was provided
		if ($this->projectValidator->isValidForCreate(["name" => $request->get('project_name')])) {
			$project = $this->projectService->createProject($request->get('project_name'), $user);
			$this->currentProjectProvider->set($project);
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
