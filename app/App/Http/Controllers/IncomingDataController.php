<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Logstats\App\Validators\IncomingDataValidator;
use Logstats\App\Validators\ValidationException;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Record\RecordServiceInterface;

class IncomingDataController extends Controller {

	public function __construct(ProjectRepository $projectRepository,
								IncomingDataValidator $incDataValidator,
								RecordServiceInterface $recordService) {
		$this->projectRepository = $projectRepository;
		$this->incDataValidator = $incDataValidator;
		$this->recordService = $recordService;
	}

	public function store(Request $request) {
		$data = $request->all();

		if (!is_array($data)) { // invalid data format
			return $this->errorResponse();
		}

		return $this->newData($data);
	}

	private function newData(array $data) {
		if (!$this->incDataValidator->isValidRoot($data)) {
			return $this->errorResponse();
		}

		// $data['project'] is already valid here
		$project = $this->projectRepository->findByWriteToken($data['project']);

		if ($project == null) {
			return $this->errorResponse('Invalid project write token');
		}

		$messages = json_decode($data['messages'], true);

		foreach ($messages as $message) {
			$this->newRecord($message, $project);
		}

		header('Access-Control-Allow-Origin: *');
	}

	private function newRecord($message, Project $project) {
		if (!is_array($message) || !$this->incDataValidator->isValidRecord($message)) {
			return false;
		}

		$context = isset($message['context']) ? $message['context'] : [];

		$this->recordService->createRecord($message['level'], $message['message'], $message['time'], $project, $context);
	}

	public function errorResponse($message = 'Invalid data format, please see documentation') {
		return response($message, 400);
	}
}