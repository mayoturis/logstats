<?php  namespace Logstats\Infrastructure\Repositories\Database;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Logstats\Domain\DTOs\ProjectLastRecordDTO;
use Logstats\Domain\DTOs\ProjectProjectRoleListDTO;
use Logstats\Domain\Project\Project;
use Logstats\Domain\Project\ProjectRoleList;
use Logstats\Domain\User\User;
use Logstats\Domain\Project\ProjectRepository;
use Logstats\Domain\User\Role;
use Logstats\Infrastructure\Repositories\Database\Factories\StdProjectFactory;
use Logstats\Infrastructure\Repositories\Database\Factories\StdUserFactory;
use Logstats\Support\Date\CarbonConvertorInterface;
use PDO;

class DbProjectRepository extends DbBaseRepository implements ProjectRepository {

	private $table = 'projects';
	private $projectRoleUserTable = 'project_role_user';
	private $recordTable = 'records';
	private $userTable = 'users';
	private $prefix;
	private $factory;
	private $connection;
	private $carbonConvertor;
	/**
	 *
	 */
	private $userFactory;

	public function __construct(StdProjectFactory $factory, CarbonConvertorInterface $carbonConvertor, StdUserFactory $userFactory) {
		$this->prefix = \DB::getTablePrefix();
		$this->connection = \DB::getPdo();
		$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$this->factory = $factory;
		$this->carbonConvertor = $carbonConvertor;
		$this->userFactory = $userFactory;
	}

	/**
	 * @param Project $project
	 * @return Project
	 */
	public function save(Project $project) {
		if ($project->getId() == null) {
			$this->insertProject($project);
		} else {
			$this->updateProject($project);
		}

		return $project;
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
			"write_token" => $project->getWriteToken(),
			"read_token" => $project->getReadToken(),
			"created_at" => $this->carbonConvertor->carbonInGMT($project->getCreatedAt())
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
		DB::table($this->table)
			->where('id', $project->getId())
			->update([
				"name" => $project->getName(),
				"write_token" => $project->getWriteToken(),
				"read_token" => $project->getReadToken(),
			]);
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
	 * @return Project
	 */
	public function findFirstBy(array $conditions) {
		$rawProject = $this->findFirstRawBy($conditions);

		if (empty($rawProject)) {
			return null;
		}

		return $this->factory->makeFromStd($rawProject);
	}

	/**
	 * Finds project by its writeToken
	 *
	 * @param string $token Project writeToken
	 * @return Project
	 */
	public function findByWriteToken($token) {
		return $this->findFirstBy(['write_token' => $token]);
	}

	/**
	 * Finds project by its readToken
	 *
	 * @param $token
	 * @return Project
	 */
	public function findByReadToken($token) {
		return $this->findFirstBy(['read_token' => $token]);
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
		return $this->factory->makeFromStdArray($rawProject);
	}

	/**
	 * Get all projects
	 *
	 * @return array of Project
	 */
	public function findAll() {
		return $this->findBy([]);
	}

	/**
	 * Return all projects and date of the latest record
	 *
	 * @return ProjectLastRecordDTO[]
	 */
	public function findAllWithLatestRecord($allowedRoles = null, $userId = null) {
		$query = 'SELECT * FROM ' . $this->prefix.$this->table . ' projects
					LEFT JOIN (
						SELECT project_id, MAX(date) as date
						FROM '. $this->prefix.$this->recordTable . '
						GROUP BY project_id
					) r ON projects.id = project_id';

		if (!is_null($allowedRoles) && !is_null($userId)) {
			$query .= " WHERE EXISTS (" . $this->projectUserRoleExistsQuery($allowedRoles, $userId, 'projects') . ")";
		}

		$query .= " ORDER BY date DESC ";
		$rows = $this->connection->query($query)->fetchAll();
		$dtos = [];
		foreach ($rows as $row) {
			$project = $this->factory->makeFromStd($row);
			$date = is_null($row->date) ? null : $this->carbonConvertor->carbonFromStandartGMTString($row->date);
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
	public function findRoleForUserInProject(User $user, Project $project) {
		$rawRole = \DB::table($this->projectRoleUserTable)
				->where('user_id', $user->getId())
				->where('project_id', $project->getId())->get(['role']);

		if (empty($rawRole)) {
			return null;
		}

		return new Role($rawRole[0]->role);
	}



	/**
	 * @param Project $project
	 */
	public function getProjectRoleList(Project $project) {
		$raw = \DB::table($this->projectRoleUserTable)
			->join($this->userTable, $this->userTable.'.id', '=',$this->projectRoleUserTable.'.user_id')
			->where('project_id', $project->getId())
			->get([$this->userTable.'.id',$this->userTable.'.role','name', 'password', 'email','remember_token', DB::raw($this->prefix.$this->projectRoleUserTable.'.role as project_role')]);

		$projectRoleList = new ProjectRoleList();
		foreach ($raw as $oneRaw) {
			$user = $this->userFactory->makeFromStd($oneRaw);
			$projectRoleList->setRole($user, new Role($oneRaw->project_role));
		}

		return $projectRoleList;
	}

	/**
	 * @return Project[]
	 */
	public function getAll() {
		return $this->findBy([]);
	}

	public function getAllProjectsWithRoleLists() {
		$projects = $this->getAll();
		$projectProjectRoleListDTOs = [];
		foreach ($projects as $project) {
			$projectRoleList = $this->getProjectRoleList($project);
			$projectProjectRoleListDTOs[] = new ProjectProjectRoleListDTO($project, $projectRoleList);
		}

		return $projectProjectRoleListDTOs;
	}

	/**
	 * @param ProjectRoleList $projectRoleList
	 * @param Project $project
	 */
	public function saveProjectRoleList(ProjectRoleList $projectRoleList, Project $project) {
		DB::table($this->projectRoleUserTable)
			->where('project_id', $project->getId())
			->delete();

		$insertRows = [];
		foreach ($projectRoleList->getUsers() as $user) {
			$insertRows[] = [
				'user_id' => $user->getId(),
				'project_id' => $project->getId(),
				'role' => $projectRoleList->getRoleForUser($user)
			];
		}

		DB::table($this->projectRoleUserTable)
			->insert($insertRows);
	}

	public function deleteProjectRoles(Project $project) {
		DB::table($this->projectRoleUserTable)
			->where('project_id', $project->getId())
			->delete();
	}

	/**
	 * @param Project $project
	 */
	public function delete(Project $project) {
		$this->deleteProjectRoles($project);

		DB::table($this->table)
			->where('id', $project->getId())
			->delete();
	}

	/**
	 * @param User $user
	 */
	public function deleteProjectRolesForUser(User $user) {
		DB::table($this->projectRoleUserTable)
			->where('user_id', $user->getId())
			->delete();
	}
}
