<?php  namespace Logstats\Services\Data; 

interface DataServiceInterface {

	/**
	 * Handle new incoming data
	 *
	 * @param array $data
	 * @return void
	 */
	public function newData(array $data);
}