<?php  namespace Logstats\Infrastructure\Repositories\Database;


abstract class DbBaseRepository {


	/**
	 * Return first occurence by conditions
	 *
	 * @param array conditions
	 * @return StdClass
	 */
	protected function findFirstRawBy(array $conditions) {
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
	protected function findRawBy(array $conditions) {
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