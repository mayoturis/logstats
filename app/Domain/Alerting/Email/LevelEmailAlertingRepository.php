<?php  namespace Logstats\Domain\Alerting\Email; 


interface LevelEmailAlertingRepository {
	public function insert(LevelEmailAlerting $levelEmailAlerting);

	/**
	 * @param $projectId
	 * @return LevelEmailAlerting[]
	 */
	public function getAllForProject($projectId);

	/**
	 * @param $id
	 * @return LevelEmailAlerting
	 */
	public function findById($id);

	/**
	 * @param LevelEmailAlerting $alerting
	 */
	public function delete(LevelEmailAlerting $alerting);
}