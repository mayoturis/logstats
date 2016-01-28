<?php  namespace Logstats\Domain\Alerting\Email;

use Logstats\Domain\Events\NewRecord;

class NewRecordEmailAlerting {

	private $levelEmailAlertingRepository;
	private $emailAlerter;

	/**
	 * @param LevelEmailAlertingRepository $levelEmailAlertingRepository
	 * @param EmailAlerterInterface $emailAlerter
	 */
	public function __construct(LevelEmailAlertingRepository $levelEmailAlertingRepository,
								EmailAlerterInterface $emailAlerter) {
		$this->levelEmailAlertingRepository = $levelEmailAlertingRepository;
		$this->emailAlerter = $emailAlerter;
	}

	/**
	 * Handles NewRecord event
	 *
	 * @param NewRecord $newRecord
	 */
	public function handle(NewRecord $newRecord) {
		$record = $newRecord->getRecord();
		$alertings = $this->levelEmailAlertingRepository->getAllForProject($record->getProjectId());

		foreach ($alertings as $alerting) {
			if ($alerting->matchRecord($record)) {
				$this->emailAlerter->sendEmailWithRecord($alerting->getEmail(), $record);
			}
		}
	}
}