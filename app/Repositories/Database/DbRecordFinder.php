<?php  namespace Logstats\Repositories\Database; 

use DB;
use Logstats\Domain\Filters\BooleanFilters\BooleanFilter;
use Logstats\Domain\Filters\NumberFilters\NumberFilter;
use Logstats\Domain\Filters\OneValueFilter;
use Logstats\Domain\Filters\RecordFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;
use Logstats\Entities\Project;
use Logstats\Repositories\Database\Factories\StdRecordFactory;
use Logstats\Repositories\Database\Filters\DbFilterFactory;
use Logstats\Repositories\Database\Filters\OneValueFilterMapper;
use Logstats\Services\Date\CarbonConvertorInterface;
use Logstats\ValueObjects\Pagination;
use Logstats\ValueObjects\RecordConditions;

class DbRecordFinder {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	private $prefix;
	private $filterFactory;

	private $stdRecordFactory;
	private $oneValueFilterMapper;
	private $carbonConvertor;

	public function __construct(StdRecordFactory $stdRecordFactory, CarbonConvertorInterface $carbonConvertor, OneValueFilterMapper $oneValueFilterMapper) {
		$this->prefix = DB::getTablePrefix();
		$this->oneValueFilterMapper = $oneValueFilterMapper;
		$this->stdRecordFactory = $stdRecordFactory;
		$this->carbonConvertor = $carbonConvertor;
	}

	public function getRecordsByConditions(Project $project, RecordFilter $conditions = null, Pagination $pagination = null) {
		$recordsBuilder = $this->getBuilderWithRecordsTable($project, $conditions, $pagination);
		$recordsBuilder->leftJoin('properties', $this->recordTable.'.id', '=', 'record_id');
		$rawRecords = $recordsBuilder->get($this->recordWithPropertiesColumns());
		return $this->stdRecordFactory->makeFromMoreRaws($rawRecords);
	}

	public function getRecordsCountByConditions(Project $project, RecordFilter $conditions = null) {
		$recordsBuilder = $this->getBuilderWithRecordsTable($project, $conditions);
		return $recordsBuilder->count();
	}

	private function addFilterWheres($queryBuilder, RecordFilter $filter) {
		foreach ($filter->getMessageFilters() as $messageFilter) {
			$queryBuilder->where('message',
				$this->oneValueFilterMapper->getComparisonOperator($messageFilter),
				$this->oneValueFilterMapper->getValue($messageFilter));
		}

		foreach ($filter->getLevelFilters() as $levelFilter) {
			$queryBuilder->where('level',
				$this->oneValueFilterMapper->getComparisonOperator($levelFilter),
				$this->oneValueFilterMapper->getValue($levelFilter));
		}

		foreach ($filter->getDateFilters() as $dateFilter) {
			$queryBuilder->where('date',
				$this->oneValueFilterMapper->getComparisonOperator($dateFilter),
				$this->oneValueFilterMapper->getValue($dateFilter));
		}

		foreach ($filter->getContextFilters() as $lastKeyArrayFilter) {
			$queryBuilder->whereExists(function ($query) use ($lastKeyArrayFilter) {
				$name = $lastKeyArrayFilter->getLastKey();
				$filter = $lastKeyArrayFilter->getFilter();
				$query->select(DB::raw(1))
					->from($this->propertiesTable)
					->whereRaw($this->prefix.$this->recordTable.'.id
							= '.$this->prefix.$this->propertiesTable. '.record_id')
					->where('value_'.$this->oneValueFilterMapper->getType($filter),
						$this->oneValueFilterMapper->getComparisonOperator($filter),
						$this->oneValueFilterMapper->getValue($filter))
					->where(function($query) use($name) {
						$query->where('name', $name)
							->orWhere('name', 'LIKE', '%.' . $name);
					});
			});
		}
	}

	private function getBuilderWithRecordsTable(Project $project, RecordFilter $conditions = null, Pagination $pagination = null) {
		$query = DB::table($this->recordTable)
			->select($this->recordColumns())
			->where($this->recordTable.'.project_id', $project->getId())
			->join('messages', 'message_id', '=', $this->messageTable.'.id');
		if ($conditions !== null) {
			$this->addFilterWheres($query,$conditions);
			if ($pagination !== null) {
				$toSkip = ($pagination->getPage() - 1) * $pagination->getPageCount();
				$query->skip($toSkip)->take($pagination->getPageCount());
			}
		}
		$query->orderBy('date');
		$recordBuiler = DB::table(DB::raw("(({$query->toSql()}) as ".$this->prefix.$this->recordTable .')'))
			->mergeBindings($query);
		return $recordBuiler;
	}

	private function recordWithPropertiesColumns() {
		return array_merge($this->recordColumns(), [
			'name',
			'value_string',
			'value_number',
			'value_boolean',
		]);
	}

	private function recordColumns() {
		return [
			$this->recordTable . '.id',
			'date',
			'message',
			'level',
			$this->recordTable.'.project_id',
		];
	}
}