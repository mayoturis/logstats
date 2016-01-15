<?php  namespace Logstats\App\Http\Controllers; 

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Logstats\App\Support\RecordFilterCreator;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\Record\RecordRepository;
use Logstats\Domain\Services\Convertors\RecordsToCsvConvertorInterface;

class ExportController extends Controller {

	private $recordFilterCreator;
	private $recordRepository;
	private $recordsToCsvConvertor;
	private $projectRepository;
	private $gate;

	public function __construct(RecordFilterCreator $recordFilterCreator,
								RecordRepository $recordRepository,
								RecordsToCsvConvertorInterface $recordsToCsvConvertor,
								ProjectRepository $projectRepository,
								Gate $gate) {
		$this->recordFilterCreator = $recordFilterCreator;
		$this->recordRepository = $recordRepository;
		$this->recordsToCsvConvertor = $recordsToCsvConvertor;
		$this->projectRepository = $projectRepository;
		$this->gate = $gate;
	}

	public function csv(Request $request) {
		$projectId = $request->get('project-id');
		$project = $this->projectRepository->findById($projectId);
		if (!$this->gate->check('showRecords', [$project])) {
			throw new UnauthorizedException('Access denied');
		}

		$recordFilter = $this->recordFilterCreator->createRecordFilterFromRequest($request);
		$records = $this->recordRepository->getRecordsByConditions($project, $recordFilter);

		$csv = $this->recordsToCsvConvertor->convertRecordsToCsvString($records);
		header("Content-type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=export.csv");
		return $csv;
	}
}