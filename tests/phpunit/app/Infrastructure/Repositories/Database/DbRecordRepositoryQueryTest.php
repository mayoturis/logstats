<?php

use Carbon\Carbon;
use Logstats\Domain\Filters\ComparisonTypes;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\Query\Intervals;
use Logstats\Domain\Record\Query\Query;
use Logstats\Domain\Record\Query\QueryException;
use Logstats\Domain\Record\Query\Where;
use Logstats\Infrastructure\Repositories\Database\DbByQueryFinder;
use Logstats\Infrastructure\Repositories\Database\DbMessageFinder;
use Logstats\Infrastructure\Repositories\Database\DbPropertyFinder;
use Logstats\Infrastructure\Repositories\Database\DbRecordDeleter;
use Logstats\Infrastructure\Repositories\Database\DbRecordFinder;
use Logstats\Infrastructure\Repositories\Database\DbRecordRepository;
use Logstats\Infrastructure\Repositories\Database\DbRecordSaver;
use Logstats\Infrastructure\Repositories\Database\Factories\StdRecordFactory;
use Logstats\Infrastructure\Repositories\Database\Filters\ComparisonTypeMapper;
use Logstats\Infrastructure\Repositories\Database\Filters\OneValueFilterMapper;
use Logstats\Support\Date\CarbonConvertor;

class DbRecordRepositoryQueryTest extends DatabaseTestCase {

	/**
	 * @var DbRecordRepository
	 */
	private $repository;
	private $queryProject;

	public function setUp() {
		parent::setUp();

		$this->queryProject = new Project('', '');
		$this->queryProject->setId(3);

		$this->repository = $this->getRepository();
	}


	/*
	 * DbByQueryFinder TESTS
	 */

	public function test_empty_message_throws_query_exception() {
		$query = new Query();
		$query->setAggregation('count');
		try {
			$result = $this->repository->getData($this->queryProject, $query);
			$this->fail('Query exception should have been thrown');
		} catch (QueryException $ex) {
			// ok
		}
	}

	public function test_invalid_message_throws_query_exception() {
		$query = new Query();
		$query->setEvent('fsdfasfsdfsdfsd');
		$query->setAggregation('count');
		try {
			$result = $this->repository->getData($this->queryProject, $query);
			$this->fail('Query exception should have been thrown');
		} catch (QueryException $ex) {
			// ok
		}
	}

	public function test_invalid_aggregation_throws_query_exception() {
		$query = $this->getQuery();
		$query->setAggregation('gol');
		try {
			$result = $this->repository->getData($this->queryProject, $query);
			$this->fail('Query exception should have been thrown');
		} catch (QueryException $ex) {
			// ok
		}
	}

	public function test_invalid_aggregation_target_throws_query_exception() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('sdfsdf');
		try {
			$result = $this->repository->getData($this->queryProject, $query);
			$this->fail('Query exception should have been thrown');
		} catch (QueryException $ex) {
			// ok
		}
	}

	public function test_records_count_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('count');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(1, count($result));
		$this->assertEquals(8, $result[0]['value']);
	}

	public function test_price_sum_all_time_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(39, $result[0]['value']);
	}

	public function test_price_average_all_time_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('avg');
		$query->setAggregationTarget('price');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(4.875, $result[0]['value']);
	}

	public function test_price_max_all_time_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('max');
		$query->setAggregationTarget('price');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(9, $result[0]['value']);
	}

	public function test_price_min_all_time_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('min');
		$query->setAggregationTarget('price');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(1, $result[0]['value']);
	}

	public function test_count_for_name_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('count');
		$query->setGroupBy('name');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(2, count($result));
		$this->assertEquals('john', $result[0]['group']);
		$this->assertEquals(4, $result[0]['value']);
		$this->assertEquals('marek', $result[1]['group']);
		$this->assertEquals(4, $result[1]['value']);
	}

	public function test_price_sum_for_name_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$query->setGroupBy('name');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals('john', $result[0]['group']);
		$this->assertEquals(23, $result[0]['value']);
		$this->assertEquals('marek', $result[1]['group']);
		$this->assertEquals(16, $result[1]['value']);
	}

	public function test_counts_in_months_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('count');
		$query->setInterval(Intervals::MONTHLY);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(2, count($result));
		$this->assertEquals(2015, $result[0]['year']);
		$this->assertEquals(11, $result[0]['month']);
		$this->assertTrue(empty($result[0]['day']));
		$this->assertEquals(4, $result[0]['value']);
		$this->assertEquals(2015, $result[1]['year']);
		$this->assertEquals(12, $result[1]['month']);
		$this->assertTrue(empty($result[1]['day']));
		$this->assertEquals(4, $result[1]['value']);
	}

	public function test_price_sum_in_months_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$query->setInterval(Intervals::MONTHLY);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(2, count($result));
		$this->assertEquals(2015, $result[0]['year']);
		$this->assertEquals(11, $result[0]['month']);
		$this->assertEquals(25, $result[0]['value']);
		$this->assertEquals(2015, $result[1]['year']);
		$this->assertEquals(12, $result[1]['month']);
		$this->assertEquals(14, $result[1]['value']);
	}

	public function test_price_max_in_years_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('max');
		$query->setAggregationTarget('price');
		$query->setInterval(Intervals::YEARLY);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(1, count($result));
		$this->assertEquals(2015, $result[0]['year']);
		$this->assertTrue(empty($result[0]['month']));
		$this->assertEquals(9, $result[0]['value']);
	}

	public function test_price_min_in_days_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('min');
		$query->setAggregationTarget('price');
		$query->setInterval(Intervals::DAILY);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(2, count($result));
		$this->assertEquals(2015, $result[0]['year']);
		$this->assertEquals(11, $result[0]['month']);
		$this->assertEquals(10, $result[0]['day']);
		$this->assertTrue(empty($result[0]['hour']));
		$this->assertEquals(4, $result[0]['value']);
		$this->assertEquals(2015, $result[1]['year']);
		$this->assertEquals(12, $result[1]['month']);
		$this->assertEquals(10, $result[1]['day']);
		$this->assertTrue(empty($result[1]['hour']));
		$this->assertEquals(1, $result[1]['value']);
	}

	public function test_price_sum_in_months_for_name_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$query->setInterval(Intervals::MONTHLY);
		$query->setGroupBy('name');

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(4, count($result));

		$this->assertEquals(11, $result[0]['month']);
		$this->assertEquals('john', $result[0]['group']);
		$this->assertEquals(13, $result[0]['value']);

		$this->assertEquals(11, $result[1]['month']);
		$this->assertEquals('marek', $result[1]['group']);
		$this->assertEquals(12, $result[1]['value']);

		$this->assertEquals(12, $result[2]['month']);
		$this->assertEquals('john', $result[2]['group']);
		$this->assertEquals(10, $result[2]['value']);

		$this->assertEquals(12, $result[3]['month']);
		$this->assertEquals('marek', $result[3]['group']);
		$this->assertEquals(4, $result[3]['value']);
	}

	public function test_price_sum_with_from_time_filter_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$firstDecember = Carbon::createFromFormat('d.m.Y H:i', '1.12.2015 12:00');
		$query->setFrom($firstDecember);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(14, $result[0]['value']);
	}

	public function test_price_sum_with_to_time_filter_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$firstDecember = Carbon::createFromFormat('d.m.Y H:i', '1.12.2015 12:00');
		$query->setTo($firstDecember);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(25, $result[0]['value']);
	}

	public function test_all_time_price_sum_with_equal_property_filter_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$where = new Where();
		$where->setComparisonType(ComparisonTypes::EQUAL_TO);
		$where->setPropertyName('name');
		$where->setPropertyValue('marek');
		$query->addWhere($where);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(16, $result[0]['value']);
	}

	public function test_all_time_count_with_less_than_property_filter_can_be_get() {
		$query = $this->getQuery();
		$query->setAggregation('count');
		$where = new Where();
		$where->setComparisonType(ComparisonTypes::LESS_THAN);
		$where->setPropertyName('price');
		$where->setPropertyValue(7);
		$query->addWhere($where);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(5, $result[0]['value']);
	}


	public function test_all_query_functions() {
		$query = $this->getQuery();
		$query->setAggregation('sum');
		$query->setAggregationTarget('price');
		$query->setInterval(Intervals::MONTHLY);
		$query->setGroupBy('name');
		$firstDecember = Carbon::createFromFormat('d.m.Y H:i', '1.12.2015 12:00');
		$query->setFrom($firstDecember);
		$where = new Where();
		$where->setComparisonType(ComparisonTypes::GREATER_THAN);
		$where->setPropertyName('price');
		$where->setPropertyValue(2);
		$query->addWhere($where);

		$result = $this->repository->getData($this->queryProject, $query);

		$this->assertEquals(2, count($result));
		$this->assertEquals(12, $result[0]['month']);
		$this->assertEquals('john', $result[0]['group']);
		$this->assertEquals(8, $result[0]['value']);
		$this->assertEquals(12, $result[1]['month']);
		$this->assertEquals('marek', $result[1]['group']);
		$this->assertEquals(3, $result[1]['value']);
	}

	private function getQuery() {
		$query = new Query();
		$query->setEvent('purchase');
		return $query;
	}

	private function getRepository() {
		$carbonConvertor = new CarbonConvertor();
		$factory = new StdRecordFactory($carbonConvertor);
		$oneValueFilterMapper = new OneValueFilterMapper($carbonConvertor);
		$propertyFinder = new DbPropertyFinder();
		$messageFinder = new DbMessageFinder($oneValueFilterMapper);
		$comparisonTypeMapper = new ComparisonTypeMapper();
		return new DbRecordRepository(
			new DbRecordSaver($carbonConvertor),
			new DbRecordFinder($factory, $carbonConvertor, $oneValueFilterMapper),
			new DbRecordDeleter(),
			$messageFinder,
			$propertyFinder,
			new DbByQueryFinder(
				$propertyFinder,
				$messageFinder,
				$oneValueFilterMapper,
				$comparisonTypeMapper,
				$carbonConvertor
			)
		);
	}
}