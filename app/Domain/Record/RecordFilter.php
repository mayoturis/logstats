<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Filters\ArrayFilters\ArrayFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;
use Logstats\Domain\Filters\TimeFilters\TimeFilter;

class RecordFilter {
	private $dateFilters = [];
	private $levelFilters = [];
	private $messageFilters = [];
	private $contextFilters = [];

	/**
	 * @param TimeFilter $timeFilter
	 */
	public function addDateFilter(TimeFilter $timeFilter) {
		$this->dateFilters[] = $timeFilter;
	}

	/**
	 * @param StringFilter $stringFilter
	 */
	public function addLevelFilter(StringFilter $stringFilter) {
		$this->levelFilters[] = $stringFilter;
	}

	/**
	 * @param StringFilter $stringFilter
	 */
	public function addMessageFilter(StringFilter $stringFilter) {
		$this->messageFilters[] = $stringFilter;
	}

	/**
	 * @param ArrayFilter $arrayFilter
	 */
	public function addContextFilter(ArrayFilter $arrayFilter) {
		$this->contextFilters[] = $arrayFilter;
	}

	/**
	 * @param Record $record
	 * @return bool
	 */
	/*public function match(Record $record) {

	}*/

	/**
	 * @return StringFilter[]
	 */
	public function getDateFilters() {
		return $this->dateFilters;
	}

	/**
	 * @return StringFilter[]
	 */
	public function getLevelFilters() {
		return $this->levelFilters;
	}

	/**
	 * @return StringFilter[]
	 */
	public function getMessageFilters() {
		return $this->messageFilters;
	}

	/**
	 * @return ArrayFilter[]
	 */
	public function getContextFilters() {
		return $this->contextFilters;
	}
}