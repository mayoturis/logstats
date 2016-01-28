<?php  namespace Logstats\Domain\Record\Query;
use Logstats\Domain\Project\Project;

interface QueryServiceInterface {

	/**
	 * Gets data for Project by given Query
	 *
	 * @param Project $project
	 * @param Query $query
	 */
	public function getData(Project $project, Query $query);
}