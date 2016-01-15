<?php  namespace Logstats\App\Providers\Project;

use Logstats\Domain\Project\Project;

interface CurrentProjectProviderInterface {

	/**
	 * Set current project
	 *
	 * @param Project $project
	 * @return void
	 */
	public function set(Project $project);

	/**
	 * Get current project
	 *
	 * @return Project
	 */
	public function get();

	/**
	 * If project is set
	 *
	 * @return bool
	 */
	public function isSetProject();

	/**
	 * Unset current project
	 */
	public function unsetProject();
}