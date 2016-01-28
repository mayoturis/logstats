<?php  namespace Logstats\Domain\Project;

use Logstats\Domain\DTOs\ProjectLastRecordDTO;
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
	 * Finds project by id
	 *
	 * @param int $id
	 * @return Project|null
	 */
	public function findById($id);

	/**
	 * Finds projects by conditions
	 *
	 * @param array $conditions
	 * @return Project[]
	 */
	public function findBy(array $conditions);

	/**
	 * Finds project by its token
	 *
	 * @param string $token Project token
	 * @return Project
	 */
	public function findByToken($token);

	/**
	 * Gets all projects
	 *
	 * @return Project[]
	 */
	public function findAll();

	/**
	 * Returns all projects and date of the latest record
	 *
	 * @return ProjectLastRecordDTO[]
	 */
	public function findAllWithLatestRecord($allowedRoles = null, $userId = null);

	/**
	 * Finds role for user in project
	 *
	 * @param User $user
	 * @param Project $project
	 * @return Role
	 */
	public function findRoleForUserInProject(User $user, Project $project);


	/**
	 * Gets ProjectRoleList for given project
	 *
	 * @param Project $project
	 * @return ProjectRoleList
	 */
	public function getProjectRoleList(Project $project);

	/**
	 * Gets all projects with its ProjectRoleLists
	 *
	 * @return ProjectProjectRoleListDTO[]
	 */
	public function getAllProjectsWithRoleLists();

	/**
	 * Gets all projects
	 *
	 * @return Project[]
	 */
	public function getAll();

	/**
	 * @param ProjectRoleList $projectRoleList
	 * @param Project $project
	 */
	public function saveProjectRoleList(ProjectRoleList $projectRoleList, Project $project);

	/**
	 * Deletes all roles for given project
	 *
	 * @param Project $project
	 */
	public function deleteProjectRoles(Project $project);

	/**
	 * Deletes project
	 *
	 * @param Project $project
	 */
	public function delete(Project $project);

	/**
	 * Deletes all roles for user in all projects
	 *
	 * @param User $user
	 */
	public function deleteProjectRolesForUser(User $user);
}