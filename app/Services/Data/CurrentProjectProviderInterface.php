<?php  namespace Logstats\Services\Data; 

use Logstats\Entities\Project;

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
}