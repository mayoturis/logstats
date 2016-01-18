<?php  namespace Logstats\Infrastructure\Repositories\Database;

use Illuminate\Support\Facades\DB;
use Logstats\Domain\Record\MessageFilter;
use Logstats\Domain\Project\Project;
use Logstats\Infrastructure\Repositories\Database\Filters\OneValueFilterMapper;
use Logstats\Domain\ValueObjects\Pagination;

class DbMessageFinder {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $oneValueFilterMapper;

	public function __construct(OneValueFilterMapper $oneValueFilterMapper) {
		$this->oneValueFilterMapper = $oneValueFilterMapper;
	}

	public function getMessagesByConditions(Project $project, MessageFilter $conditions = null, Pagination $pagination = null) {
		$queryBuilder = $this->getQueryBuilderWithConditions($project, $conditions);
		$rawMessages = $queryBuilder->get(['message', $this->messageTable.'.id']);
		return $this->getMessagesFromRaw($rawMessages);
	}

	private function getQueryBuilderWithConditions(Project $project, MessageFilter $conditions = null, Pagination $pagination = null) {
		$queryBuilder = DB::table($this->messageTable)
			->where('project_id', $project->getId());

		if (!is_null($conditions)) {
			$this->addConditionsWheres($queryBuilder, $conditions);
			if ($pagination !== null) {
				$toSkip = ($pagination->getPage() - 1) * $pagination->getPageCount();
				$queryBuilder->skip($toSkip)->take($pagination->getPageCount());
			}
		}

		return $queryBuilder;
	}

	private function addConditionsWheres($queryBuilder, Messagefilter $filter) {
		foreach ($filter->getMessageFilters() as $messageFilter) {
			$queryBuilder->where('message',
				$this->oneValueFilterMapper->getComparisonOperator($messageFilter),
				$this->oneValueFilterMapper->getValue($messageFilter));
		}
		foreach ($filter->getLevelFilters() as $levelFilter) {
			$queryBuilder->whereExists(function($query) use($levelFilter) {
				$query->select(DB::raw('1'))
					->from($this->recordTable)
					->where('level',
						$this->oneValueFilterMapper->getComparisonOperator($levelFilter),
						$this->oneValueFilterMapper->getValue($levelFilter))
					->whereRaw('message_id = '. DB::getTablePrefix().$this->messageTable . '.id');
			});
		}
	}

	public function getMessagesCountByConditions(Project $project, MessageFilter $conditions = null) {
		$queryBuilder = $this->getQueryBuilderWithConditions($project,$conditions);
		return $queryBuilder->count();
	}

	public function getProjectIdForMessageId($messageId) {
		$rows = DB::table($this->messageTable)->where('id', $messageId)->get(['project_id']);
		if (empty($rows)) {
			return null;
		}
		return $rows[0]->project_id;
	}

	// not public to DbRecordRepository
	public function getMessageidForMessageInProject($message, Project $project) {
		$raw = DB::table($this->messageTable)->where('message', $message)
			->where('project_id', $project->getId())->get(['id']);
		if (empty($raw)) {
			return null;
		}

		return $raw[0]->id;
	}

	private function getMessagesFromRaw($rawMessages) {
		$messages = [];
		foreach ($rawMessages as $rawMessages) {
			$messages[$rawMessages->id] = $rawMessages->message;
		}
		return $messages;
	}
}