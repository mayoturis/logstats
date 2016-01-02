<?php  namespace Logstats\Repositories\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Logstats\Domain\Query\AggregateFunctions;
use Logstats\Domain\Query\Intervals;
use Logstats\Domain\Query\Query;
use Logstats\Entities\Project;
use Logstats\Repositories\Database\Exceptions\PropertyTypeNotFoundException;
use Logstats\Repositories\Database\Filters\ComparisonTypeMapper;
use Logstats\Repositories\Database\Filters\OneValueFilterMapper;
use Logstats\Repositories\Exceptions\QueryException;
use Logstats\Services\Date\CarbonConvertorInterface;

class DbByQueryFinder {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	private $propertyFinder;
	private $messageFinder;
	private $oneValueFilterMapper;
	private $comparisonTypeMapper;
	private $carbonConvertor;

	/**
	 * @var Query
	 */
	private $query;
	/**
	 * @var Project
	 */
	private $project;

	/**
	 * @var int
	 */
	private $messageId;

	/**
	 * @var array
	 */
	private $propertyTypes;

	/**
	 * @var Builder
	 */
	private $queryBuilder;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @var array
	 */
	private $propertyAliases;


	public function __construct(DbPropertyFinder $propertyFinder,
								DbMessageFinder $messageFinder,
								OneValueFilterMapper $oneValueFilterMapper,
								ComparisonTypeMapper $comparisonTypeMapper,
								CarbonConvertorInterface $carbonConvertor) {
		$this->propertyFinder = $propertyFinder;
		$this->messageFinder = $messageFinder;
		$this->prefix = DB::getTablePrefix();
		$this->oneValueFilterMapper = $oneValueFilterMapper;
		$this->comparisonTypeMapper = $comparisonTypeMapper;
		$this->carbonConvertor = $carbonConvertor;
	}

	public function getData(Project $project, Query $query) {
		$this->query = $query;
		$this->project = $project;
		$this->setMessageId();
		$this->setPropertyTypes();
		$this->createPropertyAliases();
		return $this->runQuery();
	}

	private function setMessageId() {
		$messageId = $this->messageFinder->getMessageidForMessageInProject($this->query->getEvent(), $this->project);
		if ($messageId === null) {
			throw new QueryException('Event ' . $this->query->getEvent() . ' was not found in this project');
		}

		$this->messageId =  $messageId;
	}

	private function setPropertyTypes() {
		$typesNeededProperties = $this->getTypeNeededProperties();
		try {
			$this->propertyTypes = $this->propertyFinder->getPropertyTypesForMessageId($this->messageId, $typesNeededProperties);
		} catch (PropertyTypeNotFoundException $ex) {
			$message = $ex->getMessage();
			throw new QueryException($message, 0, $ex);
		}
	}

	private function getTypeNeededProperties() {
		$properties = [];
		$properties[] = $this->query->getGroupBy();
		$properties[] = $this->query->getAggregationTarget();
		foreach ($this->query->getWheres() as $where) {
			$properties[] = $where->getPropertyName();
		}
		$properties = array_unique($properties);
		$properties = array_diff($properties, [null]);
		return $properties;
	}

	private function createPropertyAliases() {
		foreach ($this->getTypeNeededProperties() as $key => $property) {
			$this->propertyAliases[$property] = 'h'.md5(microtime()) . $key;
		}
	}

	private function runQuery() {
		$this->queryBuilder = DB::table($this->recordTable);
		$this->addSelect();
		$this->addJoins();
		$this->addWheres();
		$this->addGroupBy();
		$this->addOrderBy();
		return $this->resultToArray($this->queryBuilder->get());
	}

	private function addSelect() {
		$this->queryBuilder->select($this->columnsToGet());
	}

	private function columnsToGet() {
		$columns = $this->getGroupByColumns();
		$columns[] = $this->getAggregateRawFunction();
		return $columns;
	}

	private function getAggregateRawFunction() {
		if ($this->query->getAggregation() == AggregateFunctions::COUNT) {
			$func = 'count(*)';
		} else {
			$func = $this->query->getAggregation() .
				'('. $this->propertyAliases[$this->query->getAggregationTarget()] . ')';
		}
		$func .= ' as value';
		return DB::raw($func);
	}

	private function addJoins() {
		$this->queryBuilder->join($this->messageTable, 'message_id', '=', $this->messageTable . '.id');
		foreach ($this->propertyTypes as $name => $type) {
			$this->queryBuilder->leftJoin(DB::raw("
				(SELECT record_id, value_$type as ".$this->propertyAliases[$name]."
				FROM {$this->prefix}properties
				WHERE name = ?) as ".$this->propertyAliases[$name]),
			$this->recordTable.'.id', '=', DB::raw($this->propertyAliases[$name].'.record_id'))->addBinding($name, 'select');
		}
	}

	private function addWheres() {
		$this->queryBuilder->where($this->recordTable.'.project_id', $this->project->getId());
		$this->queryBuilder->where($this->messageTable.'.id', $this->messageId);
		foreach ($this->query->getWheres() as $where) {
			$this->queryBuilder->where(
				$this->propertyAliases[$where->getPropertyName()],
				$this->comparisonTypeMapper->getOperator($where->getComparisonType()),
				$this->comparisonTypeMapper->getValue($where->getComparisonType(), $where->getPropertyValue())
			);
		}

		if ($this->query->isSetFrom()) {
			$this->queryBuilder->where('date', '>=', $this->carbonConvertor->carbonInGMT($this->query->getFrom()));
		}
		if ($this->query->isSetTo()) {
			$this->queryBuilder->where('date', '<=', $this->carbonConvertor->carbonInGMT($this->query->getTo()));
		}
	}

	private function addGroupBy() {
		$groupByColumns = $this->getGroupByColumns();
		call_user_func_array(array($this->queryBuilder, 'groupBy'), $groupByColumns);
	}

	private function getGroupByColumns() {
		$groupByColumns = [];
		if ($this->query->isSetGroupBy()) {
			$groupByColumns[] = $this->propertyAliases[$this->query->getGroupBy()];
		}
		$groupByColumns = array_merge($groupByColumns, $this->getIntervalGroupByColumns());
		return $groupByColumns;
	}

	private function getIntervalGroupByColumns() {
		if (!$this->query->isSetInterval()) {
			return [];
		}
		$interval = $this->query->getInterval();
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

	private function addOrderBy() {
		if ($this->query->isSetInterval()) {
			foreach ($this->getIntervalGroupByColumns() as $intervalColumn) {
				$this->queryBuilder->orderBy($intervalColumn);
			}
		}
		if ($this->query->isSetGroupBy()) {
			$this->queryBuilder->orderBy($this->propertyAliases[$this->query->getGroupBy()]);
		}
	}

	private function resultToArray($rawResult) {
		$rows = [];
		foreach ($rawResult as $rawRow) {
			if ($rawRow->value === null)
				continue;

			$row['value'] = $rawRow->value;
			foreach ($this->getIntervalGroupByColumns() as $intervalColumn) {
				$row[$intervalColumn] = $rawRow->$intervalColumn;
			}
			if ($this->query->isSetGroupBy()) {
				$row['group'] = $rawRow->{$this->propertyAliases[$this->query->getGroupBy()]};
				if ($row['group'] === null) {
					continue;
				}
			}
			$rows[] = $row;
		}

		return $rows;
	}
}