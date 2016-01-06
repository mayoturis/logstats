<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
		$jsonData = $request->get('data');
		$data = json_decode($jsonData,true);

		if (!is_array($data)) { // invalid data format
			throw new \UnexpectedValueException('Data has to be array');
		}

		$this->newData($data);
	}

	private function newData(array $data) {
		if (!$this->incDataValidator->isValidRoot($data)) {
			throw new ValidationException($this->incDataValidator->getErrors(), 'Invalid data');
		}

		// $data['project'] is already valid here
		$project = $this->projectRepository->findByToken($data['project']);

		foreach ($data['messages'] as $message) {
			$this->newRecord($message, $project);
		}
	}

	private function newRecord($message, Project $project) {
		if (!$this->incDataValidator->isValidRecord($message)) {
			return false;
		}

		$context = isset($message['context']) ? $message['context'] : [];

		$this->recordService->createRecord($message['level'], $message['message'], $message['time'], $project, $context);
	}
}