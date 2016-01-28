<?php  namespace Logstats\Infrastructure\Repositories\Database; 

use Illuminate\Support\Facades\DB;
use Logstats\Domain\Alerting\Email\LevelEmailAlertingRepository;
use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Infrastructure\Repositories\Database\Factories\StdLevelEmailAlertingFactory;

class DbLevelEmailAlertingRepository extends DbBaseRepository implements LevelEmailAlertingRepository {

	private $table = 'email_send';

	private $stdLevelEmailAlertingFactory;

	public function __construct(StdLevelEmailAlertingFactory $stdLevelEmailAlertingFactory) {
		$this->stdLevelEmailAlertingFactory = $stdLevelEmailAlertingFactory;
	}

	/**
	 * @param LevelEmailAlerting $levelEmailAlerting
	 */
	public function insert(LevelEmailAlerting $levelEmailAlerting) {
		$id = DB::table($this->table)
			->insertGetId([
				'project_id' => $levelEmailAlerting->getProjectId(),
				'email' => $levelEmailAlerting->getEmail(),
				'level' => $levelEmailAlerting->getLevel()
			]);
		$levelEmailAlerting->setId($id);
	}

	/**
	 * Gets all alertings for project
	 *
	 * @param int $projectId
	 * @return LevelEmailAlerting[]
	 */
	public function getAllForProject($projectId) {
		$raw = $this->findRawBy(['project_id' => $projectId]);
		return $this->stdLevelEmailAlertingFactory->makeFromStdArray($raw);
	}

	protected function getTable() {
		return $this->table;
	}

	/**
	 * @param int $id
	 * @return LevelEmailAlerting
	 */
	public function findById($id) {
		return $this->findFirstBy(['id' => $id]);
	}

	/**
	 * @param array $conditions
	 * @return LevelEmailAlerting[]
	 */
	public function findBy(array $conditions) {
		$rawAlertings = $this->findRawBy($conditions);
		return $this->stdLevelEmailAlertingFactory->makeFromStdArray($rawAlertings);
	}

	/**
	 * @param array $conditions
	 * @return LevelEmailAlerting|null
	 */
	public function findFirstBy(array $conditions) {
		$rawAlerting = $this->findFirstRawBy($conditions);

		if (empty($rawAlerting)) {
			return null;
		}

		return $this->stdLevelEmailAlertingFactory->makeFromStd($rawAlerting);
	}

	/**
	 * @param LevelEmailAlerting $alerting
	 */
	public function delete(LevelEmailAlerting $alerting) {
		DB::table($this->table)
			->where('id', $alerting->getId())
			->delete();
	}

	/**
	 * Deletes all alertings for project
	 *
	 * @param int $projectId
	 */
	public function deleteForProject($projectId) {
		DB::table($this->table)
			->where('project_id', $projectId)
			->delete();
	}
}