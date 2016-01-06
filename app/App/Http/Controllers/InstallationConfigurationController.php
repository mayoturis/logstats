<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\Domain\Services\Database\DatabaseConfigServiceInterface;
use Logstats\Domain\Services\Installation\InstallationServiceInterface;
use Logstats\Domain\Services\Installation\StepCollection;
use Logstats\Domain\Services\Installation\Steps;
use Logstats\App\Validators\ValidationException;

class InstallationConfigurationController extends Controller {

	private $steps;
	private $installationService;
	private $databaseConfig;


	public function __construct(StepCollection $steps,
								DatabaseConfigServiceInterface $databaseConfig,
								InstallationServiceInterface $installationService) {
		$this->steps = $steps;
		$this->installationService = $installationService;
		$this->databaseConfig = $databaseConfig;
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
}