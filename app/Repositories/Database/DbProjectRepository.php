<?php  namespace Logstats\Repositories\Database;

use Carbon\Carbon;
use Logstats\DTOs\ProjectLastRecordDTO;
use Logstats\Services\Factories\ProjectFactoryInterface;
use Logstats\Entities\Project;
use Logstats\Entities\User;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\ValueObjects\Role;
use PDO;

class DbProjectRepository extends DbBaseRepository implements ProjectRepository {

	private $table = 'projects';
	private $projectRoleUserTable = 'project_role_user';
	private $recordTable = 'records';
	private $prefix;
	private $factory;
	private $connection;

	/**
	 * @param ProjectFactoryInterface $factory
	 */
	public function __construct(ProjectFactoryInterface $factory) {
		$this->factory = $factory;

		$this->prefix = \DB::getTablePrefix();
		$this->connection = \DB::getPdo();
		$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}

	/**
	 * @param Project $project
	 * @return Project
	 */
	public function save(Project $project) {
		if ($project->getId() == null) {
			return $this->insertProject($project);
		} else {
			return $this->updateProject($project);
		}
	}


	/**
	 * Insert new project and set new id
	 *
	 * @param Project $project
	 * @return Project
	 */
	private function insertProject(Project $project) {
		$now = Carbon::now('GMT');
		$id = \DB::table($this->table)->insertGetId([
			"name" => $project->getName(),
			"token" => $project->getToken(),
			"created_at" => $this->dateInGMT($project->getCreatedAt()),
		]);

		$project->setId($id);
		return $project;
	}

	/**
	 * Update project
	 *
	 * @param Project $project
	 * @return Project
	 */
	private function updateProject(Project $project) {
		\DB::table($this->table)->where('id', $project->getId())
			->update([
				"name" => $project->getName(),
				"token" => $project->getName()
			]);

		return $project;
	}

	/**
	 * Adds user for the project
	 *
	 * @param Project $project
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addUserToProject(Project $project, User $user, Role $role) {
		$count = \DB::table($this->projectRoleUserTable)
			->where('user_id', $user->getId())
			->where('project_id', $project->getId())
			->where('role', $role)->count();

		if ($count == 0) {
			\DB::table($this->projectRoleUserTable)->insert([
				'user_id' => $user->getId(),
				'project_id' => $project->getId(),
				'role' => $role
			]);
		}
	}

	/**
	 * Find project by id
	 *
	 * @param int $id
	 * @return Project|null
	 */
	public function findById($id) {
		return $this->findFirstBy(['id' => $id]);

	}

	/**
	 * Find first user by conditions
	 *
	 * @param array $conditions
	 * @return User
	 */
	public function findFirstBy(array $conditions) {
		$rawProject = $this->findFirstRawBy($conditions);

		if (empty($rawProject)) {
			return null;
		}

		return $this->factory->makeFromStd($rawProject);
	}

	/**
	 * @param Carbon $date
	 * @return Carbon
	 */
	private function dateInGMT(Carbon $date) {
		$new = Carbon::createFromFormat('Y-m-d H:i:s', $date);
		$new->setTimezone('GMT');
		return $new;
	}

	/**
	 * @param $time
	 * @return Carbon
	 */
	private function carbonFromGMTTime($time) {
		$t = Carbon::now();
		$tz = $t->getTimezone();
		$carbon = Carbon::createFromFormat('Y-m-d H:i:s', $time, 'GMT');
		$carbon->setTimezone($tz);
		return $carbon;
	}

	/**
	 * Find project by its token
	 *
	 * @param string $token Project token
	 * @return Project
	 */
	public function findByToken($token) {
		return $this->findFirstBy(['token' => $token]);
	}

	/**
	 * @return string Table name
	 */
	protected function getTable() {
		return $this->table;
	}

	/**
	 * Find projects by conditions
	 *
	 * @param array $conditions
	 * @return array of Project
	 */
	public function findBy(array $conditions) {
		$rawProject = $this->findRawBy($conditions);
		return $this->rawProjectsToArrayOfProjects($rawProject);
	}

	/**
	 * Get all projects
	 *
	 * @return array of Project
	 */
	public function findAll() {
		return $this->findBy([]);
	}

	private function rawProjectsToArrayOfProjects($rawProjects) {
		$projects = [];
		foreach ($rawProjects as $rawProject) {
			$projects[] = $this->factory->makeFromStd($rawProject);
		}

		return $projects;
	}

	/**
	 * Return all projects and date of the latest record
	 *
	 * @return array
	 */
	public function findAllWithLatestRecord($allowedRoles = null, $userId = null) {
		$query = 'SELECT * FROM ' . $this->prefix.$this->table . ' projects
					LEFT JOIN (
						SELECT project_id, date
						FROM '. $this->prefix.$this->recordTable . '
						ORDER BY date DESC
						LIMIT 1
					) r ON projects.id = project_id';

		if (!is_null($allowedRoles) && !is_null($userId)) {
			$query .= " WHERE EXISTS (" . $this->projectUserRoleExistsQuery($allowedRoles, $userId, 'projects') . ")";
		}

		$rows = $this->connection->query($query)->fetchAll();

		$dtos = [];
		foreach ($rows as $row) {
			$project = $this->factory->makeFromStd($row);
			$date = is_null($row->date) ? null : $this->carbonFromGMTTime($row->date);
			$dtos[] = new ProjectLastRecordDTO($project, $date);
		}

		return $dtos;
	}

	private function projectUserRoleExistsQuery(array $allowedRoles, $userId, $projectTable = null) {
		$projectTable = !empty($projectTable) ? $projectTable : $this->prefix.$this->table;
		$table = $this->prefix . $this->projectRoleUserTable;
		$query = " SELECT 1 FROM $table WHERE ";

		$subQueries = [];
		foreach ($allowedRoles as $role) {
			$subQueries[] = " ($table.role = " . $this->q($role) . "
						   AND $table.user_id = " . $this->q($userId, PDO::PARAM_INT) . "
						   AND $table.project_id = $projectTable.id) ";
		}
		$query .= implode(' OR ', $subQueries);
		return $query;
	}

	private function q($string, $param = PDO::PARAM_STR) {
		return $this->connection->quote($string, $param);
	}

	/**
	 * @param User $user
	 * @param Project $project
	 * @return array of Role
	 */
	public function findRolesForUserInProject(User $user, Project $project) {
		$rawRoles = \DB::table($this->projectRoleUserTable)
				->where('user_id', $user->getId())
				->where('project_id', $project->getId())->get(['role']);
		return $this->rawRolesToRoleArray($rawRoles);
	}

	private function rawRolesToRoleArray($rawRoles) {
		$roles = [];
		foreach ($rawRoles as $rawRole) {
			$roles[] = new Role($rawRole->role);
		}
		return $roles;
	}
}
