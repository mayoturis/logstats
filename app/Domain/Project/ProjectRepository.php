<?php  namespace Logstats\Domain\Project;

use Logstats\Domain\DTOs\ProjectProjectRoleListDTO;
use Logstats\Domain\User\User;
use Logstats\Domain\User\Role;

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
	 * Find projects by conditions
	 *
	 * @param array $conditions
	 * @return Project[]
	 */
	public function findBy(array $conditions);

	/**
	 * Find project by its token
	 *
	 * @param string $token Project token
	 * @return Project
	 */
	public function findByToken($token);

	/**
	 * Get all projects
	 *
	 * @return Project[]
	 */
	public function findAll();

	/**
	 * Return all projects and date of the latest record
	 *
	 * @return array
	 */
	public function findAllWithLatestRecord($allowedRoles = null, $userId = null);

	/**
	 * @param User $user
	 * @param Project $project
	 * @return Role[]
	 */
	public function findRoleForUserInProject(User $user, Project $project);


	/**
	 * @param Project $project
	 */
	public function getProjectRoleList(Project $project);

	/**
	 * @return ProjectProjectRoleListDTO[]
	 */
	public function getAllProjectsWithRoleLists();

	/**
	 * @return Project[]
	 */
	public function getAll();

	/**
	 * @param ProjectRoleList $projectRoleList
	 * @param Project $project
	 */
	public function saveProjectRoleList(ProjectRoleList $projectRoleList, Project $project);

	public function deleteProjectRoles(Project $project);

	/**
	 * @param Project $project
	 */
	public function delete(Project $project);

	/**
	 * @param User $user
	 */
	public function deleteProjectRolesForUser(User $user);
}