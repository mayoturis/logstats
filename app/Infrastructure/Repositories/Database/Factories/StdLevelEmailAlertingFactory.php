<?php  namespace Logstats\Infrastructure\Repositories\Database\Factories;

use Logstats\Domain\Alerting\Email\LevelEmailAlerting;

class StdLevelEmailAlertingFactory {
	public function makeFromStd($stdObject) {
		$levelEmailAlerting = new LevelEmailAlerting(
			$stdObject->project_id,
			$stdObject->level,
			$stdObject->email
		);
		$levelEmailAlerting->setId($stdObject->id);

		return $levelEmailAlerting;
	}

	public function makeFromStdArray($stdArray) {
		$results = [];
		foreach ($stdArray as $stdObject) {
			$results[] = $this->makeFromStd($stdObject);
		}
		return $results;
	}
}