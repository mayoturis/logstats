<?php  namespace Logstats\Infrastructure\Repositories\Database;

use DB;
use Logstats\Domain\Record\Query\Intervals;
use Logstats\Domain\Record\RecordFilter;
use Logstats\Domain\Project\Project;
use Logstats\Infrastructure\Repositories\Database\Factories\StdRecordFactory;
use Logstats\Infrastructure\Repositories\Database\Filters\OneValueFilterMapper;
use Logstats\Support\Date\CarbonConvertorInterface;
use Logstats\Domain\ValueObjects\Pagination;

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
		$recordsBuilder->orderBy('date');
		$rawRecords = $recordsBuilder->get($this->recordWithPropertiesColumns());
		return $this->stdRecordFactory->makeFromStdArray($rawRecords);
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
		$recordBuiler = DB::table(DB::raw("({$query->toSql()}) as ".$this->prefix.$this->recordTable .''))
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
			DB::raw($this->prefix.$this->recordTable . '.id as id'),
			'date',
			'message',
			'level',
			'year',
			'month',
			'day',
			'hour',
			'minute',
			DB::raw($this->prefix.$this->recordTable.'.project_id as project_id'),
		];
	}

	public function getRecordsCountInInterval(Project $project, $interval, RecordFilter $recordFilter = null) {
		$recordsBuilder = $this->getBuilderWithRecordsTable($project, $recordFilter);
		$groupByColumns = $this->getIntervalGroupByColumns($interval);
		call_user_func_array(array($recordsBuilder, 'groupBy'), $groupByColumns);
		$columnsToGet = array_merge($groupByColumns, [DB::raw('COUNT(*) as value')]);
		$raw = $recordsBuilder->get($columnsToGet);
		return $this->resultToArray($raw, $groupByColumns);
	}

	private function getIntervalGroupByColumns($interval) {
		$columns = ['year'];
		if ($interval == Intervals::YEARLY) {
			return $columns;
		}
		$columns[] = 'month';
		if ($interval == Intervals::MONTHLY) {
			return $columns;
		}
		$columns[] = 'day';
		if ($interval == Intervals::DAILY) {
			return $columns;
		}
		$columns[] = 'hour';
		if ($interval == Intervals::HOURLY) {
			return $columns;
		}
		$columns[] = 'minute';
		return $columns;
	}

	private function resultToArray($rawResult, $groupByColumns) {
		$rows = [];
		foreach ($rawResult as $rawRow) {
			$row['value'] = $rawRow->value;
			foreach ($groupByColumns as $column) {
				$row[$column] = $rawRow->$column;
			}
			$rows[] = $row;
		}

		return $rows;
	}
}