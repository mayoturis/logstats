<?php  namespace Logstats\Services\Factories;

use Carbon\Carbon;
use Logstats\Entities\Project;

class ProjectFactory implements ProjectFactoryInterface {

	/**
	 * @param string|null $id
	 * @param string|null $name
	 * @param string|null $token
	 * @param string|null $createdAt Time in GMT
	 * @return Project
	 */
	public function make($id = null, $name = null, $token = null, Carbon $createdAt = null) {
		$project =  new Project($name, $token);
		$project->setId($id);

		if ($createdAt === null) {
			$project->setCreatedAt(Carbon::now());
		} else {
			$project->setCreatedAt($createdAt);
		}
		return $project;
	}

	/**
	 * Create entity from object
	 *
	 * @param Object $data
	 * @return Project
	 */
	public function makeFromStd($stdObject) {
		$project = $this->make(
			$stdObject->id,
			$stdObject->name,
			$stdObject->token,
			$this->carbonFromGMTTime($stdObject->created_at)
		);

		return $project;
	}

	/**
	 * @param $time
	 * @return Carbon
	 */
	private function carbonFromGMTTime($time) {
		$t = Carbon::now();
		$tz = $t->getTimezone();
		$carbon = Carbon::createFromFormat('Y-m-d H:i:s', $time, 'GMT');
		$carbon->setTimezone($tz);
		return $carbon;
	}
}