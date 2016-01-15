<?php  namespace Logstats\App\Http\Controllers; 


use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Logstats\App\Support\RecordFilterCreator;
use Logstats\Domain\Filters\ArrayFilters\LastKeyArrayFilter;
use Logstats\Domain\Filters\Factories\GeneralFilterFactory;
use Logstats\Domain\Record\MessageFilter;
use Logstats\Domain\Record\Query\Intervals;
use Logstats\Domain\Record\RecordFilter;
use Logstats\Domain\Filters\StringFilters\ContainsFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\StringFilters\StartsWithFilter;
use Logstats\Domain\Filters\TimeFilters\FromFilter;
use Logstats\Domain\Filters\TimeFilters\ToFilter;
use Logstats\Domain\Record;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Support\Date\CarbonConvertorInterface;
use Logstats\Domain\Record\RecordServiceInterface;
use Logstats\Domain\Record\PropertyFilterFactory;
use Logstats\App\Validators\RecordValidator;
use Logstats\Domain\ValueObjects\Pagination;

class RecordController extends Controller {

	private $carbonConvertor;
	private $recordValidator;
	private $recordService;
	private $gate;
	private $recordRepository;
	private $projectRepository;
	private $generalFilterFactory;
	private $recordFilterCreator;

	public function __construct(CarbonConvertorInterface $carbonConvertor,
								RecordValidator $recordValidator,
								RecordServiceInterface $recordService,
								Gate $gate,
								RecordRepository $recordRepository,
								ProjectRepository $projectRepository,
								GeneralFilterFactory $generalFilterFactory,
								RecordFilterCreator $recordFilterCreator) {
		$this->carbonConvertor = $carbonConvertor;
		$this->recordValidator = $recordValidator;
		$this->recordService = $recordService;
		$this->gate = $gate;
		$this->recordRepository = $recordRepository;
		$this->projectRepository = $projectRepository;
		$this->generalFilterFactory = $generalFilterFactory;
		$this->recordFilterCreator = $recordFilterCreator;
	}

	public function ajaxShow(Request $request) {
		$projectId = $request->get('project-id');
		$project = $this->projectRepository->findById($projectId);
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$filter = $this->recordFilterCreator->createRecordFilterFromRequest($request);
		$pagination = $this->createPaginationFromRequest($request);
		$records = $this->recordRepository->getRecordsByConditions($project, $filter,$pagination);
		$recordsCountWithoutPagination = $this->recordRepository->getRecordsCountByConditions($project,$filter);
		$graphData = null;
		if ($request->has('from') && $request->has('to')) { // get graph only when timeframe is set
			$graphData = $this->recordService->getRecordsCountInInterval($project, $this->determineBestInterval((int) $request->get('from'), (int) $request->get('to')), $filter);
		}
		$view = view('log._log_partial')->with(['records' => $records]);
		$html = $view->render();
		return response()->json([
			'html' => $html,
			'count' => urlencode($recordsCountWithoutPagination),
			'graphData' => [
				'data' => $graphData,
				'timeframe' => [
					'from' => (int) $request->get('from'),
					'to' => (int) $request->get('to')
				],
			]
		]);
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

	private function createPaginationFromRequest(Request $request) {
		$pagination = null;
		if (!empty($request->get('page')) && !empty($request->get('page-count'))) {
			$page = (int) $request->get('page');
			$pageCount = (int) $request->get('page-count');
			$pagination = new Pagination($page, $pageCount);
		}
		return $pagination;
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

	private function determineBestInterval($from, $to) {
		$timeframeInMinutes = ($to - $from) / 60;
		if ($timeframeInMinutes < 6 * 60) {
			return Intervals::MINUTELY;
		}
		if ($timeframeInMinutes < 10  * 24 * 60) {
			return Intervals::HOURLY;
		}
		if ($timeframeInMinutes < 7 * 30 * 24 * 60) {
			return Intervals::DAILY;
		}
		if ($timeframeInMinutes < 10 * 12 * 30 * 24 * 60) {
			return Intervals::MONTHLY;
		}

		return Intervals::YEARLY;
	}
}