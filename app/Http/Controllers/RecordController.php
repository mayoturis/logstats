<?php  namespace Logstats\Http\Controllers; 


use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Logstats\Domain\Filters\ArrayFilters\LastKeyArrayFilter;
use Logstats\Domain\Filters\Factories\GeneralFilterFactory;
use Logstats\Domain\Filters\Factories\RecordFilterFromArrayFactory;
use Logstats\Domain\Filters\MessageFilter;
use Logstats\Domain\Filters\RecordFilter;
use Logstats\Domain\Filters\StringFilters\ContainsFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\StringFilters\StartsWithFilter;
use Logstats\Domain\Filters\TimeFilters\FromFilter;
use Logstats\Domain\Filters\TimeFilters\ToFilter;
use Logstats\Entities\Record;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\Repositories\Contracts\RecordRepository;
use Logstats\Services\Data\DataServiceInterface;
use Logstats\Services\Date\CarbonConvertorInterface;
use Logstats\Services\Entities\RecordServiceInterface;
use Logstats\Services\Factories\PropertyFilterFactory;
use Logstats\Services\Validators\RecordValidator;
use Logstats\ValueObjects\MessageConditions;
use Logstats\ValueObjects\Pagination;

class RecordController extends Controller {

	private $dataService;
	private $carbonConvertor;
	private $recordValidator;
	private $recordService;
	private $gate;
	private $recordRepository;
	private $projectRepository;
	private $propertyFilterFactory;
	private $generalFilterFactory;

	public function __construct(DataServiceInterface $dataService,
								CarbonConvertorInterface $carbonConvertor,
								RecordValidator $recordValidator,
								RecordServiceInterface $recordService,
								Gate $gate,
								RecordRepository $recordRepository,
								ProjectRepository $projectRepository,
								PropertyFilterFactory $propertyFilterFactory,
								GeneralFilterFactory $generalFilterFactory) {
		$this->dataService = $dataService;
		$this->carbonConvertor = $carbonConvertor;
		$this->recordValidator = $recordValidator;
		$this->recordService = $recordService;
		$this->gate = $gate;
		$this->recordRepository = $recordRepository;
		$this->projectRepository = $projectRepository;
		$this->propertyFilterFactory = $propertyFilterFactory;
		$this->generalFilterFactory = $generalFilterFactory;
	}

	public function ajaxShow(Request $request) {
		$projectId = $request->get('project-id');
		$project = $this->projectRepository->findById($projectId);
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$filter = $this->createRecordFilterFromRequest($request);
		$pagination = $this->createPaginationFromRequest($request);
		$records = $this->recordRepository->getRecordsByConditions($project, $filter,$pagination);
		$recordsCountWithoutPagination = $this->recordRepository->getRecordsCountByConditions($project,$filter);
		$view = view('log._log_partial')->with(['records' => $records]);
		$html = $view->render();
		return response()->json([
			'html' => $html,
			'count' => urlencode($recordsCountWithoutPagination)
		]);
	}

	public function store(Request $request) {
		$jsonData = $request->get('data');
		$data = json_decode($jsonData,true);

		if (!is_array($data)) { // invalid data format
			throw new \UnexpectedValueException('Data has to be array');
		}

		$this->dataService->newData($data);
	}

	public function ajaxMessages(Request $request) {
		$project = $this->projectRepository->findById($request->get('project-id'));
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$filter = $this->messageFilterFromRequest($request);
		$pagination = $this->createPaginationFromRequest($request);
		$messages = $this->recordRepository->getMessagesByConditions($project, $filter, $pagination);
		$totalCount = $this->recordRepository->getMessagesCountByConditions($project, $filter);
		return response()->json([
			'items' => $messages,
			'total_count' => $totalCount
		]);
	}

	public function ajaxPropertyNames(Request $request) {
		$messageId = (int) $request->get('message-id');
		$projectId = $this->recordRepository->getProjectIdForMessageId($messageId);
		$project = $this->projectRepository->findById($projectId);
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}
		$properties = $this->recordRepository->getPropertyNamesForMessageId($messageId);
		return response()->json($properties);
	}

	private function createRecordFilterFromRequest(Request $request) {
		$recordFilter = new RecordFilter();

		if (!empty($request->get('from'))) {
			$from = $this->carbonConvertor->carbonFromTimestampUTC((int) $request->get('from'));
			$recordFilter->addDateFilter(new FromFilter($from));
		}
		if(!empty($request->get('to'))) {
			$to = $this->carbonConvertor->carbonFromTimestampUTC((int) $request->get('to'));
			$recordFilter->addDateFilter(new ToFilter($to));
		}
		if (!empty($request->get('message-search'))) {
			$messageSearch = (string) $request->get('message-search');
			$recordFilter->addMessageFilter(new ContainsFilter($messageSearch));
		}
		if (!empty($request->get('level'))) {
			$level = (string) $request->get('level');
			$recordFilter->addLevelFilter(new EqualToFilter($level));
		}
		foreach ($this->getArrayFiltersFromRequest($request) as $filter) {
			$recordFilter->addContextFilter($filter);
		}

		return $recordFilter;
	}

	private function createPaginationFromRequest(Request $request) {
		$pagination = null;
		if (!empty($request->get('page')) && !empty($request->get('page-count'))) {
			$page = (int) $request->get('page');
			$pageCount = (int) $request->get('page-count');
			$pagination = new Pagination($page, $pageCount);
		}
		return $pagination;
	}

	private function getArrayFiltersFromRequest(Request $request) {
		$requstFilters = $request->get('filters');
		if (!is_array($requstFilters)) {
			return [];
		}

		$arrayFilters = [];
		foreach ($requstFilters as $filter) {
			if ($this->recordValidator->isValidFilter($filter)) {
				$arrayFilters[] = new LastKeyArrayFilter(
					$filter['property-name'],
					$this->generalFilterFactory->make(
						$filter['property-value'],
						$filter['property-type'],
						$filter['comparison-type']
					));
			}
		}

		return $arrayFilters;
	}

	private function getFilterObjects($arrayFilters) {
		if (!is_array($arrayFilters)) {
			return [];
		}

		$filters = [];
		foreach ($arrayFilters as $arrayFilter) {
			if ($this->recordValidator->isValidFilter($arrayFilter)) {
				$filter = $this->propertyFilterFactory->make(
					$arrayFilter['property-name'],
					$arrayFilter['property-value'],
					$arrayFilter['property-type'],
					$arrayFilter['comparison-type']
				);
				$filters[] = $filter;
			}
		}

		return $filters;
	}

	private function messagesFromRecords($records) {
		$messages = [];
		foreach($records as $record) {
			$messages[] = $record->getMessage();
		}
		return $messages;
	}

	private function arrayRecordsToJson($records) {
		$arrayRecords = [];
		foreach ($records as $record) {
			$arrayRecords[] = $this->recordToArray($record);
		}

		return json_encode($arrayRecords);
	}

	private function recordToArray(Record $record) {
		return [
			'id' => $record->getId(),
			'level' => $record->getLevel(),
			'message' => $record->getMessage(),
			'date' => (string) $record->getDate(),
			'context' => $record->getContext(),
		];
	}

	private function messageFilterFromRequest(Request $request) {
		$messageFilter = new MessageFilter();
		if (!empty($request->get('message-search'))) {
			$messageSearch = (string) $request->get('message-search');
			$messageFilter->addMessageFilter(new StartsWithFilter($messageSearch));
		}
		if (!empty($request->get('level'))) {
			$level = $request->get('level');
			$messageFilter->addLevelFilter(new EqualToFilter($level));
		}

		return $messageFilter;
	}
}