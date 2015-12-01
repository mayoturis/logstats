<?php  namespace Logstats\Services\Data; 


use Logstats\Entities\Project;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Services\Entities\RecordServiceInteraface;
use Logstats\Services\Factories\RecordFactoryInterface;
use Logstats\Services\Validators\IncomingDataValidator;

class DataService implements DataServiceInterface{

	/**
	 * @var ProjectRepository
	 */
	private $projectRepository;
	/**
	 *
	 */
	private $incDataValidator;
	/**
	 *
	 */
	private $recordService;


	public function __construct(ProjectRepository $projectRepository,
								IncomingDataValidator $incDataValidator,
								RecordServiceInteraface $recordService) {
		$this->projectRepository = $projectRepository;
		$this->incDataValidator = $incDataValidator;
		$this->recordService = $recordService;
	}

	public function newData(array $data) {
		if (!$this->incDataValidator->isValidData($data)) {
			throw new \InvalidArgumentException('Invalid data');
		}

		// $data['project'] is already valid here
		$project = $this->projectRepository->findByToken($data['project']);

		foreach ($data['messages'] as $message) {
			$this->newRecord($message, $project);
		}
	}

	private function newRecord($message, Project $project) {
		if ($this->incDataValidator->isValidRecord($message)) {
			return false;
		}

		$context = isset($message['context']) ? $message['context'] : [];

		$this->recordService->createRecord($message['level'], $message['message'], $message['time'], $project, $context);
	}
}