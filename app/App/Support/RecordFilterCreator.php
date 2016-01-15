<?php  namespace Logstats\App\Support; 
use Illuminate\Http\Request;
use Logstats\App\Validators\RecordValidator;
use Logstats\Domain\Filters\ArrayFilters\LastKeyArrayFilter;
use Logstats\Domain\Filters\Factories\GeneralFilterFactory;
use Logstats\Domain\Filters\StringFilters\ContainsFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\TimeFilters\FromFilter;
use Logstats\Domain\Filters\TimeFilters\ToFilter;
use Logstats\Domain\Record\RecordFilter;
use Logstats\Support\Date\CarbonConvertorInterface;

class RecordFilterCreator {

	private $recordValidator;
	private $carbonConvertor;
	private $generalFilterFactory;

	public function __construct(RecordValidator $recordValidator,
								CarbonConvertorInterface $carbonConvertor,
								GeneralFilterFactory $generalFilterFactory) {
		$this->recordValidator = $recordValidator;
		$this->carbonConvertor = $carbonConvertor;
		$this->generalFilterFactory = $generalFilterFactory;
	}

	public function createRecordFilterFromRequest(Request $request) {
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
}