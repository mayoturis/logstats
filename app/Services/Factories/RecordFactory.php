<?php  namespace Logstats\Services\Factories; 

use Logstats\Entities\Project;
use Logstats\Entities\Record;

class RecordFactory implements RecordFactoryInterface{

	/**
	 * @param array$message
	 * @param Project $project
	 * @return Record
	 */
	public function make($message, Project $project) {
		$record = new Record(
			$message['message'],
			Carbon::createFromTimeStampUTC($message['time']),
			$message['level'],
			$project
		);

		if (is_array($message['properties'])) {
			$record->setProperties($message['properties']);
		}
	}
}