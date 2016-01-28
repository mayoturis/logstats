<?php  namespace Logstats\Domain\Alerting\Email; 


interface LevelEmailAlertingRepository {

	/**
	 * @param LevelEmailAlerting $levelEmailAlerting
	 */
	public function insert(LevelEmailAlerting $levelEmailAlerting);

	/**
	 * Gets all alertings for project
	 *
	 * @param int $projectId
	 * @return LevelEmailAlerting[]
	 */
	public function getAllForProject($projectId);

	/**
	 * @param int $id
	 * @return LevelEmailAlerting
	 */
	public function findById($id);

	/**
	 * @param LevelEmailAlerting $alerting
	 */
	public function delete(LevelEmailAlerting $alerting);

	/**
	 * Deletes all alertings for project
	 *
	 * @param int $projectId
	 */
	public function deleteForProject($projectId);
}