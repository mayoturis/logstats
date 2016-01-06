<?php  namespace Logstats\Domain\Project;

use Logstats\Domain\User\User;
use Logstats\Domain\User\Role;

interface ProjectServiceInterface {

	/**
	 * Creates project
	 *
	 * @param string $name Name of the project
	 * @param User $user User which is creating the project
	 * @return Project
	 */
	public function createProject($name, User $user);


	/**
	 * Adds new user to the project
	 *
	 * @param Project $project
	 * @param User $user
	 * @param Role $role
	 * @return void
	 */
	public function addUserToProject(Project $project, User $user, Role $role);
}