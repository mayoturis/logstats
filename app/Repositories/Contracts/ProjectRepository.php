<?php  namespace Logstats\Repositories\Contracts;

use Logstats\Entities\Project;
use Logstats\Entities\User;
use Logstats\ValueObjects\Role;

interface ProjectRepository {

	/**
	 * @param Project $project
	 * @return Project
	 */
	public function save(Project $project);


	/**
	 * Adds user for the project
	 *
	 * @param Project $project
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addUserToProject(Project $project, User $user, Role $role);

	/**
	 * Find project by id
	 *
	 * @param int $id
	 * @return Project|null
	 */
	public function findById($id);

	/**
	 * Find project by its token
	 *
	 * @param string $token Project token
	 * @return Project
	 */
	public function findByToken($token);
}