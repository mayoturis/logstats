<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Filters\StringFilters\StringFilter;

class MessageFilter {
	private $messageFilters = [];
	private $levelFilters = [];

	public function addMessageFilter(StringFilter $filter) {
		$this->messageFilters[] = $filter;
	}

	public function addLevelFilter(StringFilter $filter) {
		$this->levelFilters[] = $filter;
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
	public function getLevelFilters() {
		return $this->levelFilters;
	}
}