<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\App\Providers\Project\CurrentProjectProviderInterface;
use Logstats\App\Validators\AlertingValidator;
use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Domain\Project\ProjectRepository;

class AlertingController extends Controller{

	private $currentProjectProvider;
	private $guard;
	private $projectRepository;
	private $alertingValidator;
	private $levelEmailAlertingRepository;

	public function __construct(CurrentProjectProviderInterface $currentProjectProvider,
								Guard $guard,
								ProjectRepository $projectRepository,
								AlertingValidator $alertingValidator,
								LevelEmailAlertingRepository $levelEmailAlertingRepository) {
		$this->currentProjectProvider = $currentProjectProvider;
		$this->guard = $guard;
		$this->projectRepository = $projectRepository;
		$this->alertingValidator = $alertingValidator;
		$this->levelEmailAlertingRepository = $levelEmailAlertingRepository;
	}

	public function index() {
		$project = $this->currentProjectProvider->get();
		if (! $this->guard->check('manageAlerting', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$alertings = $this->levelEmailAlertingRepository->getAllForProject($project->getid());
		return view('alerting.indexx')->with('alertings', $alertings);
	}

	public function store(Request $request) {
		if (!$this->alertingValidator->isValidLevelEmailAlerting($request->all())) {
			$errors = $this->alertingValidator->getErrors();
			return redirect()->back()->withInput()->withErrors($errors, 'alerting');
		}

		$project = $this->projectRepository->findById($request->get('project_id'));
		if (! $this->guard->check('manageAlerting', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$levelEmailAlerting = new LevelEmailAlerting($project->getId(), $request->get('level'), $request->get('email'));
		$this->levelEmailAlertingRepository->insert($levelEmailAlerting);

		return redirect()->back()->with([
			'flash_message' => 'Successfully saved',
			'flash_type' => 'success'
		]);
	}

	public function destroy($id) {
		$project = $this->currentProjectProvider->get();
		if (! $this->guard->check('manageAlerting', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$alerting = $this->levelEmailAlertingRepository->findById($id);

		if ($alerting === null) {
			abort(404);
		}

		$this->levelEmailAlertingRepository->delete($alerting);

		return redirect()->back()->with([
			'flash_message' => 'Successfully deleted',
			'flash_type' => 'success',
		]);
	}
}