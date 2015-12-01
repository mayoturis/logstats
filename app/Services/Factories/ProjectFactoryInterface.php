<?php  namespace Logstats\Services\Factories;

use Carbon\Carbon;
use Logstats\Entities\Project;

interface ProjectFactoryInterface extends StdFactory {

	/**
	 * @param string|null $id
	 * @param string|null $name
	 * @param string|null $token
	 * @return Project
	 */
	public function make($id = null, $name = null, $token = null, Carbon $createdAt = null);
}