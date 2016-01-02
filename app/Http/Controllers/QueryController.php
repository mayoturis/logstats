<?php  namespace Logstats\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\Domain\Query\Factories\QueryFromArrayFactory;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Repositories\Contracts\RecordRepository;
use Logstats\Repositories\Exceptions\QueryException;
use Logstats\Services\Data\QueryServiceInterface;
use Logstats\Services\Validators\QueryValidator;

class QueryController extends Controller {

	private $projectRepository;
	private $queryFromArrayFactory;
	private $queryValidator;
	private $recordRepository;
	private $queryService;

	public function __construct(ProjectRepository $projectRepository,
								QueryFromArrayFactory $queryFromArrayFactory,
								QueryValidator $queryValidator,
								RecordRepository $recordRepository,
								QueryServiceInterface $queryService) {
		$this->projectRepository = $projectRepository;
		$this->queryFromArrayFactory = $queryFromArrayFactory;
		$this->queryValidator = $queryValidator;
		$this->recordRepository = $recordRepository;
		$this->queryService = $queryService;
	}

	public function get(Request $request) {
		$project = $this->projectRepository->findByToken($request->get('projectToken'));
		if ($project === null) {
			return response(['Invalid project token'], 400);
		}
		if(!$this->queryValidator->isValidQuery($request->get('query'))) {
			$errors = $this->queryValidator->getArrayErrors();
			return response($errors, 400);
		}

		$query = $this->queryFromArrayFactory->make($request->get('query'));
		try {
			$data = $this->queryService->getData($project, $query);
		} catch(QueryException $ex) {
			return response([$ex->getMessage()], 400);
		}

		$timeFrame = $query->getTimeFrame();

		return [
			'timeframe' => $timeFrame,
			'data' => $data,
		];
	}
}