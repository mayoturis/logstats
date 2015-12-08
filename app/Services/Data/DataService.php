<?php  namespace Logstats\Services\Data; 


use Logstats\Entities\Project;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Services\Entities\RecordServiceInterface;
use Logstats\Services\Validators\IncomingDataValidator;
use Logstats\Services\Validators\ValidationException;

class DataService implements DataServiceInterface{

	private $projectRepository;
	private $incDataValidator;
	private $recordService;


	public function __construct(ProjectRepository $projectRepository,
								IncomingDataValidator $incDataValidator,
								RecordServiceInterface $recordService) {
		$this->projectRepository = $projectRepository;
		$this->incDataValidator = $incDataValidator;
		$this->recordService = $recordService;
	}

	public function newData(array $data) {
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