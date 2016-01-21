<?php

use Logstats\Infrastructure\Repositories\Database\Factories\StdRecordFactory;
use Logstats\Support\Date\CarbonConvertor;

class StdRecordFactoryTest extends TestCase{
	public function test_makeFromMoreRows() {
		$rawRecords = $this->createThreeRawRecordRaws();
		$stdRecordFactory = new StdRecordFactory(new CarbonConvertor());
		$records = $stdRecordFactory->makeFromStdArray($rawRecords);

		$this->assertEquals(2, count($records));
		$r1 = $records[0];
		$r2 = $records[1];
		$this->assertEquals(1, $r1->getId());
		$this->assertEquals('l1', $r1->getLevel());
		$this->assertEquals('m1', $r1->getMessage());
		$this->assertEquals(1, $r1->getProjectId());
		$this->assertEquals(['isAdmin' => true], $r1->getContext());

		$this->assertEquals(2, $r2->getId());
		$this->assertEquals(['name' => 'marek', 'age' => 20], $r2->getContext());
	}

	private function createThreeRawRecordRaws() {
		$r1 = new stdClass;
		$r1->id = 1;
		$r1->date = '2015-12-04 23:47:09';
		$r1->level = 'l1';
		$r1->message = 'm1';
		$r1->project_id = 1;
		$r1->name = 'isAdmin';
		$r1->value_string = null;
		$r1->value_number = null;
		$r1->value_boolean = 'marek';

		$r2 = new stdClass;
		$r2->id = 2;
		$r2->date = '2015-12-04 23:47:09';
		$r2->level = 'l2';
		$r2->message = 'm2';
		$r2->project_id = 1;
		$r2->name = 'name';
		$r2->value_string = 'marek';
		$r2->value_number = null;
		$r2->value_boolean = null;

		$r3 = new stdClass;
		$r3->id = 2;
		$r3->date = '2015-12-04 23:47:09';
		$r3->level = 'l2';
		$r3->message = 'm2';
		$r3->project_id = 1;
		$r3->name = 'age';
		$r3->value_string = null;
		$r3->value_number = 20;
		$r3->value_boolean = null;

		return [$r1, $r2, $r3];
	}
}