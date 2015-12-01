<?php  namespace Logstats\Services\Factories;


interface StdFactory {

	/**
	 * Create entity from object
	 *
	 * @param Object $data
	 * @return Entity
	 */
	public function makeFromStd($stdObject);
}