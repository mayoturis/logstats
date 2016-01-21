<?php

use Logstats\Domain\Alerting\Email\EmailAlerterInterface;
use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Domain\Alerting\Email\NewRecordEmailAlerting;
use Logstats\Domain\Events\NewRecord;
use Logstats\Domain\Record\Record;

class NewRecordEmailAlertingTest extends TestCase {
	public function test_handle_calls_email_alerter_on_matched_alertings() {
		$levelEmailAlertingRepository = Mockery::mock(LevelEmailAlertingRepository::class);
		$emailAlerter = Mockery::mock(EmailAlerterInterface::class);
		$newRecordEmailAlerting = new NewRecordEmailAlerting($levelEmailAlertingRepository, $emailAlerter);
		$newRecordEvent = Mockery::mock(NewRecord::class);
		$alerting1 = $this->getEmailAlerting();
		$alerting2 = $this->getEmailAlerting();
		$alerting3 = $this->getEmailAlerting();
		$record = Mockery::mock(Record::class);
		$projectId = 'some_project_id';

		$newRecordEvent->shouldReceive('getRecord')->once()->withNoArgs()->andReturn($record);
		$record->shouldReceive('getProjectId')->withNoArgs()->andReturn($projectId);
		$levelEmailAlertingRepository->shouldReceive('getAllForProject')->with($projectId)->andReturn([$alerting1, $alerting2, $alerting3]);

		$alerting1->shouldReceive('matchRecord')->once()->with($record)->andReturn(true);
		$alerting1->shouldReceive('getEmail')->once()->withNoArgs()->andReturn('email1');
		$alerting2->shouldReceive('matchRecord')->once()->with($record)->andReturn(false);
		$alerting3->shouldReceive('matchRecord')->once()->with($record)->andReturn(true);
		$alerting3->shouldReceive('getEmail')->once()->withNoArgs()->andReturn('email3');

		$emailAlerter->shouldReceive('sendEmailWithRecord')->once()->with('email1', $record);
		$emailAlerter->shouldReceive('sendEmailWithRecord')->once()->with('email3', $record);
		$newRecordEmailAlerting->handle($newRecordEvent);

	}

	private function getEmailAlerting() {
		return Mockery::mock(LevelEmailAlerting::class);
	}


}