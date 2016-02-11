<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\App\Validators\DatabaseConfigValidator;
use Logstats\App\Installation\Database\DatabaseConfigServiceInterface;
use Logstats\App\Installation\InstallationServiceInterface;
use Logstats\App\Installation\StepCollection;
use Logstats\App\Installation\Steps;
use Logstats\App\Validators\ValidationException;

class InstallationConfigurationController extends Controller {

	private $steps;
	private $installationService;
	private $databaseConfig;
	private $databaseConfigValidator;


	public function __construct(StepCollection $steps,
								DatabaseConfigValidator $databaseConfigValidator,
								DatabaseConfigServiceInterface $databaseConfig,
								InstallationServiceInterface $installationService) {
		$this->steps = $steps;
		$this->installationService = $installationService;
		$this->databaseConfig = $databaseConfig;
		$this->databaseConfigValidator = $databaseConfigValidator;
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
		if (!$this->databaseConfigValidator->isValidDatabaseSetup($request->all())) {
			$errors = $this->databaseConfigValidator->getErrors();
			return redirect()
				->route('installation', ['step' => $this->steps->getKeyByShort(Steps::DATABASE_SETUP)])
				->withInput()
				->withErrors($errors, 'databaseSetup');
		}

		$this->databaseConfig->saveConfiguration($request->all());

		$this->installationService->setNextInstallationStep(Steps::DATABASE_SETUP);

		return redirect()
			->route('installation', ['step' => $this->steps->nextKeyForShort(Steps::DATABASE_SETUP)]);
	}
}