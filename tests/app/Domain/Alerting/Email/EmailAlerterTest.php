<?php

use Illuminate\Contracts\Mail\Mailer;
use Logstats\Domain\Alerting\Email\EmailAlerter;
use Logstats\Domain\Record\Record;

class EmailAlerterTest extends TestCase {
	public function test_it_sends_email() {
		$mailer = $this->getMailer();
		$emailAlerter = new EmailAlerter($mailer);
		$record = $this->getRecord();
		$email = 'some_email';
		$mailer->shouldReceive('send')->once();


		$emailAlerter->sendEmailWithRecord($email, $record);
	}

	private function getMailer() {
		return Mockery::mock(Mailer::class);
	}

	private function getRecord() {
		return Mockery::mock(Record::class);
	}
}