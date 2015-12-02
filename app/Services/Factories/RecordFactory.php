<?php  namespace Logstats\Services\Factories; 

use Logstats\Entities\Project;
use Logstats\Entities\Record;

class RecordFactory implements RecordFactoryInterface{


	public function make($level, $message, $timestamp, Project $project, array $context = []) {

	}
}