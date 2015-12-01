<?php  namespace Logstats\Repositories; 

use Carbon\Carbon;
use Logstats\Services\Factories\ProjectFactoryInterface;
use Logstats\Entities\Project;
use Logstats\Entities\User;
use Logstats\Repositories\Contracts\ProjectRepository;
use Logstats\ValueObjects\Role;

class DbProjectRepository extends DbBaseRepository implements ProjectRepository {

	private $table = 'projects';
	private $projectRoleUserTable = 'project_role_user';
	/**
	 *
	 */
	private $factory;

	/**
	 * @param ProjectFactoryInterface $factory
	 */
	public function __construct(ProjectFactoryInterface $factory) {
		$this->factory = $factory;
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
		$raw = $this->findByOne(['id' => $id]);

		return $this->factory->makeFromStd($raw);
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
		$raw = $this->findByOne(['token' => $token]);

		return $this->factory->makeFromStd($raw);
	}

	/**
	 * @return string Table name
	 */
	protected function getTable() {

	}
}