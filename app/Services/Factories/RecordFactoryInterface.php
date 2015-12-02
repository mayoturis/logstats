<?php  namespace Logstats\Services\Factories; 

use Logstats\Entities\Project;
use Logstats\Entities\Record;

interface RecordFactoryInterface {

	/**
	 * @param $message
	 * @param Project $project
	 * @return Record
	 */
	public function make($level, $message, $timestamp, Project $project, array $context = []);
}