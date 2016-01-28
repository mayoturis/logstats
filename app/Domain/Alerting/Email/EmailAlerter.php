<?php  namespace Logstats\Domain\Alerting\Email; 

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Log;
use Logstats\Domain\Record\Record;
use Swift_SwiftException;

class EmailAlerter implements EmailAlerterInterface{

	private $mailer;

	/**
	 * @param Mailer $mailer
	 */
	public function __construct(Mailer $mailer) {
		$this->mailer = $mailer;
	}

	/**
	 * Sends email with record
	 *
	 * @param string $email
	 * @param Record $record
	 */
	public function sendEmailWithRecord($email, Record $record) {
		try {
			$this->mailer->send('email.recordalert', ['record' => $record], function($m) use ($email) {
				$m->to($email)->subject('New record arrived');
			});
		} catch(Swift_SwiftException $ex) {
			Log::alert('Mail cannot be sent: ' . $ex->getMessage());
		}
	}
}