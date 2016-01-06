<?php  namespace Logstats\Domain\Record\Query\Factories;

use Logstats\Domain\Record\Query\Query;
use Logstats\Domain\Record\Query\Where;
use Logstats\Support\Date\CarbonConvertorInterface;

class QueryFromArrayFactory {

	private $carbonConvertor;

	public function __construct(CarbonConvertorInterface $carbonConvertor) {
		$this->carbonConvertor = $carbonConvertor;
	}

	/**
	 * @param array $data
	 * @return Query
	 */
	public function make(array $data) {
		$query = new Query();
		$query->setEvent($data['event']);
		$query->setAggregation($data['aggregate']);
		if(!empty($data['targetProperty'])) {
			$query->setAggregationTarget($data['targetProperty']);
		}
		if(!empty($data['groupBy'])) {
			$query->setGroupBy($data['groupBy']);
		}
		if(!empty($data['interval'])) {
			$query->setInterval($data['interval']);
		}
		if(!empty($data['from'])) {
			$query->setFrom($this->carbonConvertor->carbonFromTimestampUTC($data['from']));
		}
		if(!empty($data['to'])) {
			$query->setTo($this->carbonConvertor->carbonFromTimestampUTC($data['to']));
		}

		if(!empty($data['filters']) && is_array($data['filters'])) {
			foreach ($data['filters'] as $dataWhere) {
				$query->addWhere($this->makeWhere($dataWhere));
			}
		}

		return $query;
	}

	private function makeWhere($dataWhere) {
		$where = new Where();
		$where->setPropertyName($dataWhere['propertyName']);
		$where->setPropertyValue($dataWhere['propertyValue']);
		$where->setComparisonType($dataWhere['comparisonType']);
		return $where;
	}
}