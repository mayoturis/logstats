<?php  namespace Logstats\Infrastructure\Repositories\Database; 

use Illuminate\Support\Facades\DB;
use Logstats\Domain\Project\Project;

class DbRecordDeleter {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	public function deleteRecordsForProject(Project $project) {
		DB::table($this->propertiesTable)
			->whereIn('record_id', function($query) use ($project) {
				$query->select('id')
					->from($this->recordTable)
					->where('project_id', $project->getId());
			})
			->delete();

		DB::table($this->propertyTypesTable)
			->whereIn('message_id', function($query) use ($project) {
				$query->select('id')
					->from($this->messageTable)
					->where('project_id', $project->getId());
			})
			->delete();

		DB::table($this->recordTable)
			->where('project_id', $project->getId())
			->delete();

		DB::table($this->messageTable)
			->where('project_id', $project->getId())
			->delete();
	}
}