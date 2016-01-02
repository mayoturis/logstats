<?php  namespace Logstats\Services\Data; 
use Logstats\Domain\Query\Query;
use Logstats\Entities\Project;

interface QueryServiceInterface {
	public function getData(Project $project, Query $query);
}