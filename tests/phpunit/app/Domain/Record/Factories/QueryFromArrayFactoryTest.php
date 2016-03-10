<?php

use Logstats\Domain\Record\Query\Factories\QueryFromArrayFactory;
use Logstats\Support\Date\CarbonConvertor;

class QueryFromArrayFactoryTest  extends Testcase{

	public function test_query_can_be_created() {
		$event = 'some_event';
		$aggregate = 'some_aggregation';
		$targetProperty = 'some_target_property';
		$groupBy = 'some_group_by';
		$interval = 'interval';
		$from = 10000;
		$to = 50000;
		$wherePropertyName = 'some_property_name';
		$wherePropertyValue = 'some_property_value';
		$whereComparisonType = 'some_comparison_type';
		$data = [
			'event' => $event,
			'aggregate' => $aggregate,
			'targetProperty' => $targetProperty,
			'groupBy' => $groupBy,
			'interval' => $interval,
			'from' => $from,
			'to' => $to,
			'filters' => [[
				'propertyName' => $wherePropertyName,
				'propertyValue' => $wherePropertyValue,
				'comparisonType' => $whereComparisonType
			]]
		];

		$factory = new QueryFromArrayFactory(new CarbonConvertor());
		$query = $factory->make($data);

		$this->assertEquals($event, $query->getEvent());
		$this->assertEquals($aggregate, $query->getAggregation());
		$this->assertEquals($targetProperty, $query->getAggregationTarget());
		$this->assertEquals($groupBy, $query->getGroupBy());
		$this->assertEquals($interval, $query->getInterval());
		$this->assertEquals($from, $query->getFrom()->timestamp);
		$this->assertEquals($to, $query->getTo()->timestamp);

		$wheres = $query->getWheres();
		$this->assertEquals(1, count($wheres));
		$this->assertEquals($wherePropertyName,$wheres[0]->getPropertyName());
		$this->assertEquals($wherePropertyValue,$wheres[0]->getPropertyValue());
		$this->assertEquals($whereComparisonType,$wheres[0]->getComparisonType());
	}
}