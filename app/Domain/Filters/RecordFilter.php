<?php  namespace Logstats\Domain\Filters; 

use Logstats\Domain\Filters\ArrayFilters\ArrayFilter;
use Logstats\Domain\Filters\StringFilters\StringFilter;
use Logstats\Domain\Filters\TimeFilters\TimeFilter;
use Logstats\Entities\Record;

class RecordFilter {
	private $dateFilters = [];
	private $levelFilters = [];
	private $messageFilters = [];
	private $contextFilters = [];

	public function addDateFilter(TimeFilter $timeFilter) {
		$this->dateFilters[] = $timeFilter;
	}

	public function addLevelFilter(StringFilter $stringFilter) {
		$this->levelFilters[] = $stringFilter;
	}

	public function addMessageFilter(StringFilter $stringFilter) {
		$this->messageFilters[] = $stringFilter;
	}

	public function addContextFilter(ArrayFilter $arrayFilter) {
		$this->contextFilters[] = $arrayFilter;
	}

	public function match(Record $record) {

	}

	/**
	 * @return array
	 */
	public function getDateFilters() {
		return $this->dateFilters;
	}

	/**
	 * @return array
	 */
	public function getLevelFilters() {
		return $this->levelFilters;
	}

	/**
	 * @return array
	 */
	public function getMessageFilters() {
		return $this->messageFilters;
	}

	/**
	 * @return array
	 */
	public function getContextFilters() {
		return $this->contextFilters;
	}
}