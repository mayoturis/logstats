<?php  namespace Logstats\Services\Entities; 

use Logstats\Entities\Project;
use Logstats\Entities\User;
use Logstats\ValueObjects\Role;

interface ProjectServiceInterface {

	/**
	 * Creates project
	 *
	 * @param string $name Name of the project
	 * @param User $user User which is creating the project
	 * @return \Logstats\Entities\Project
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