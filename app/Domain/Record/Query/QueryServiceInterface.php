<?php  namespace Logstats\Domain\Record\Query;
use Logstats\Domain\Project\Project;

interface QueryServiceInterface {
	public function getData(Project $project, Query $query);
}