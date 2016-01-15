<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Record\RecordService;

class ProjectManagementController extends Controller{

	private $projectRepository;
	private $guard;
	private $recordService;

	public function __construct(ProjectRepository $projectRepository,
								RecordService $recordService,
								Guard $guard) {
		$this->projectRepository = $projectRepository;
		$this->guard = $guard;
		$this->recordService = $recordService;
	}

	public function index() {
		return view('projectmanagement.index');
	}

	public function deleteRecords(Request $request) {
		$project = $this->projectRepository->findById($request->get('project-id'));

		if (!$this->guard->check('deleteRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$this->recordService->deleteRecordsForProject($project);

		return redirect()->back()->with([
			'flash_message' => 'Records successfully deleted',
			'flash_type' => 'success'
		]);
	}
}