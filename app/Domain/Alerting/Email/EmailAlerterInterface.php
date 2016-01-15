<?php  namespace Logstats\Domain\Alerting\Email; 

use Logstats\Domain\Record\Record;

interface EmailAlerterInterface {
	public function sendEmailWithRecord($email, Record $record);
}