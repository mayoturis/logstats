<?php  namespace Logstats\Services\Factories;

interface ArrayFactory {

	/**
	 * Create entity from array
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function makeFromArray($data);
}