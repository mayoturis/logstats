<?php  namespace Logstats\Repositories\Contracts; 

interface BaseRepository {

	/**
	 * Return first occurence by conditions
	 *
	 * @param array conditions
	 * @return StdClass
	 */
	public function findByOne(array $conditions);

	/**
	 * Return all value by conditions
	 *
	 * @param array $conditions
	 * @return array of StdClass
	 */
	public function findBy(array $conditions);
}