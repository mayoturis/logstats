<?php  namespace Logstats\Repositories; 

use Logstats\Repositories\Contracts\BaseRepository;
use Logstats\Repositories\Contracts\Entity;

abstract class DbBaseRepository implements BaseRepository {


	/**
	 * Return first occurence by conditions
	 *
	 * @param array conditions
	 * @return StdClass
	 */
	public function findByOne(array $conditions) {
		$query = \DB::table($this->getTable());

		foreach ($conditions as $key => $value) {
			$query->where($key, $value);
		}

		return $query->first();
	}

	/**
	 * Return all value by conditions
	 *
	 * @param array $conditions
	 * @return array of StdClass
	 */
	public function findBy(array $conditions) {
		$query = \DB::table($this->getTable());

		foreach ($conditions as $key => $value) {
			$query->where($key, $value);
		}

		return $query->get();
	}

	/**
	 * @return string Table name
	 */
	abstract protected function getTable();
}