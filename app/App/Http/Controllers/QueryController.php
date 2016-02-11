<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\Domain\Record\Query\Factories\QueryFromArrayFactory;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Domain\Record\Query\QueryException;
use Logstats\Domain\Record\Query\QueryServiceInterface;
use Logstats\App\Validators\QueryValidator;

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
		$project = $this->projectRepository->findByReadToken($request->get('projectToken'));
		if ($project === null) {
			return response(['Invalid project read token'], 400);
		}
		if(!$this->queryValidator->isValidQuery($request->get('query'))) {
			$errors = $this->queryValidator->getArrayErrors();
			return response($errors, 400, [
				'Access-Control-Allow-Origin' => '*'
			]);
		}

		$query = $this->queryFromArrayFactory->make($request->get('query'));
		try {
			$data = $this->queryService->getData($project, $query);
		} catch(QueryException $ex) {
			return response([$ex->getMessage()], 400);
		}

		$timeFrame = $query->getTimeFrame();
		return response([
			'timeframe' => $timeFrame,
			'data' => $data,
		], 200, [
			'Access-Control-Allow-Origin' => '*'
		]);
	}
}