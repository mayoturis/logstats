<?php  namespace Logstats\Domain\Alerting\Email; 

use Logstats\Domain\Record\Record;

interface EmailAlerterInterface {

	/**
	 * Sends email with record
	 *
	 * @param string $email
	 * @param Record $record
	 * @return void
	 */
	public function sendEmailWithRecord($email, Record $record);
}