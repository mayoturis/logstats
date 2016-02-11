<?php  namespace Logstats\Infrastructure\Repositories\Database\Factories; 
use Logstats\Domain\Project\Project;
use Logstats\Support\Date\CarbonConvertorInterface;

class StdProjectFactory {

	private $carbonConvertor;

	public function __construct(CarbonConvertorInterface $carbonConvertor) {
		$this->carbonConvertor = $carbonConvertor;
	}

	/**
	 * Create entity from object
	 *
	 * @param Object $data
	 * @return Project
	 */
	public function makeFromStd($stdObject) {
		$project = new Project(
			$stdObject->name,
			$stdObject->write_token,
			$stdObject->read_token
		);
		$project->setCreatedAt($this->carbonConvertor->carbonFromStandartGMTString($stdObject->created_at));
		$project->setId($stdObject->id);
		return $project;
	}

	public function makeFromStdArray($stdArray) {
		$projects = [];
		foreach ($stdArray as $stdObject) {
			$projects[] = $this->makeFromStd($stdObject);
		}

		return $projects;
	}
}