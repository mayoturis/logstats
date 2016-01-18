<?php
use Carbon\Carbon;
use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Domain\Record\Record;

class LevelEmailAlertingTest extends TestCase {
	public function test_matchRecord_returns_true_if_recored_is_matched() {
		$message = 'some_message';
		$level = 'some_level';
		$projectId = 5;
		$record = new Record($level, $message, $this->getCarbon(), $projectId);

		$levelEmailAlerter = new LevelEmailAlerting($projectId, $level, 'email');

		$this->assertTrue($levelEmailAlerter->matchRecord($record));
	}

	public function test_matchRecord_returns_false_if_record_is_not_matched() {
		$message = 'some_message';
		$level = 'some_level';
		$projectId = 5;
		$record = new Record($level, $message, $this->getCarbon(), $projectId);

		$levelEmailAlerter1 = new LevelEmailAlerting($projectId, 'other_level', 'email');
		$levelEmailAlerter2 = new LevelEmailAlerting('other_project', $level, 'email');

		$this->assertFalse($levelEmailAlerter1->matchRecord($record));
		$this->assertFalse($levelEmailAlerter2->matchRecord($record));
	}

	public function getCarbon() {
		return Mockery::mock(Carbon::class);
	}

}