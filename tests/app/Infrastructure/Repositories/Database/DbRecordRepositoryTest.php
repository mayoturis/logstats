<?php

use Carbon\Carbon;
use Logstats\Domain\Filters\ArrayFilters\LastKeyArrayFilter;
use Logstats\Domain\Filters\NumberFilters\GreaterThanFilter;
use Logstats\Domain\Filters\StringFilters\ContainsFilter;
use Logstats\Domain\Filters\StringFilters\EqualToFilter;
use Logstats\Domain\Filters\StringFilters\StartsWithFilter;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Record\MessageFilter;
use Logstats\Domain\Record\Query\Query;
use Logstats\Domain\Record\Record;
use Logstats\Domain\Record\RecordFilter;
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

class DbRecordRepositoryTest extends DatabaseTestCase {

	/**
	 * @var DbRecordRepository
	 */
	private $repository;
	private $currentProject1RecordCount = 6;
	private $project1;
	private $queryProject;

	public function setUp() {
		parent::setUp();

		$this->project1 = new Project('','');
		$this->project1->setId(1);

		$this->queryProject = new Project('', '');
		$this->queryProject->setId(3);

		$this->repository = $this->getRepository();
	}
	/*
	 * DbRrecordSaver TESTS
	 */

	public function test_record_can_be_saved() {
		$properties = ['property' => 'value', 'another' => ['something' => 'anything', 'number' => 5, 'bool' => true]];
		$record = new Record('emergency', 'somemessage', Carbon::now(), 1, $properties);

		$this->repository->newRecord($record);

		$records = $this->repository->getRecordsByConditions($this->project1);

		$newRecord = array_pop($records);
		$this->assertEquals($this->currentProject1RecordCount + 1, $this->repository->getRecordsCountByConditions($this->project1));
		$this->assertEquals('emergency', $newRecord->getLevel());
		$this->assertEquals('somemessage', $newRecord->getMessage());
		$this->assertEquals($properties, $newRecord->getContext());
		$this->assertEquals(1, $newRecord->getProjectId());
	}

	/*
	 * DbRrecordFinder TESTS
	 */

	public function test_records_can_be_found_by_level() {
		$recordFilter = new RecordFilter();
		$recordFilter->addLevelFilter(new EqualToFilter('info'));

		$records = $this->repository->getRecordsByConditions($this->project1, $recordFilter);

		$this->assertEquals(4, count($records));
		foreach ($records as $record) {
			$this->assertEquals('info', $record->getLevel());
		}
	}

	public function test_records_can_be_found_by_message() {
		$recordFilter = new RecordFilter();
		$recordFilter->addMessageFilter(new ContainsFilter('1'));

		$records = $this->repository->getRecordsByConditions($this->project1, $recordFilter);

		$this->assertEquals(2, count($records));
		foreach ($records as $record) {
			$this->assertContains('1', $record->getMessage());
		}
	}

	public function test_records_can_be_found_by_context() {
		$recordFilter = new RecordFilter();
		$recordFilter->addContextFilter(new LastKeyArrayFilter('name', new EqualToFilter('name2')));

		$records = $this->repository->getRecordsByConditions($this->project1, $recordFilter);

		$this->assertEquals(1, count($records));
		$context = $records[0]->getContext();
		$this->assertEquals('name2', $context['name']);
	}

	public function test_records_can_be_found_by_context_2() {
		$recordFilter = new RecordFilter();
		$recordFilter->addContextFilter(new LastKeyArrayFilter('age', new GreaterThanFilter(5)));

		$records = $this->repository->getRecordsByConditions($this->project1, $recordFilter);

		$this->assertEquals(1, count($records));
		$context = $records[0]->getContext();
		$this->assertEquals(10, $context['age']);
	}

	public function test_record_count_by_condition_can_be_determined() {
		$recordFilter = new RecordFilter();
		$recordFilter->addLevelFilter(new EqualToFilter('info'));

		$count = $this->repository->getRecordsCountByConditions($this->project1, $recordFilter);

		$this->assertEquals(4, $count);
	}

	public function test_record_count_by_condition_can_be_determined_2() {
		$allRecordsCount = $this->repository->getRecordsCountByConditions($this->project1);

		$this->assertEquals($this->currentProject1RecordCount, $allRecordsCount);
	}

	/*
	 * DbRrecordDeleter TESTS
	 */

	public function test_all_records_for_project_can_be_deleted() {
		$this->repository->deleteRecordsForProject($this->project1);

		$this->assertEquals(0, $this->repository->getRecordsCountByConditions($this->project1));
	}

	/*
	 * DbMessageFinder TESTS
	 */
	public function test_messages_can_be_found_by_message() {
		$messageFilter = new MessageFilter();
		$messageFilter->addMessageFilter(new StartsWithFilter('mess'));

		$messages = $this->repository->getMessagesByConditions($this->project1, $messageFilter);

		$this->assertEquals(3, count($messages));
		$this->assertTrue(in_array('message1', $messages));
		$this->assertTrue(in_array('message2', $messages));
		$this->assertTrue(in_array('message3', $messages));
	}

	public function test_messages_can_be_found_by_level() {
		$messageFilter = new MessageFilter();
		$messageFilter->addLevelFilter(new EqualToFilter('info'));

		$messages = $this->repository->getMessagesByConditions($this->project1, $messageFilter);

		$this->assertEquals(2, count($messages));
		$this->assertTrue(in_array('message1', $messages));
		$this->assertTrue(in_array('message3', $messages));
	}

	public function test_messegase_count_can_be_get() {
		$count = $this->repository->getMessagesCountByConditions($this->project1);

		$this->assertEquals(4, $count);
	}

	public function test_messegase_count_can_be_get_2() {
		$messageFilter = new MessageFilter();
		$messageFilter->addMessageFilter(new StartsWithFilter('mess'));

		$count = $this->repository->getMessagesCountByConditions($this->project1, $messageFilter);

		$this->assertEquals(3, $count);
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