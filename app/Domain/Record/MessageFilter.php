<?php  namespace Logstats\Domain\Record;

use Logstats\Domain\Filters\StringFilters\StringFilter;

class MessageFilter {
	private $messageFilters = [];
	private $levelFilters = [];

	/**
	 * @param StringFilter $filter
	 */
	public function addMessageFilter(StringFilter $filter) {
		$this->messageFilters[] = $filter;
	}

	/**
	 * @param StringFilter $filter
	 */
	public function addLevelFilter(StringFilter $filter) {
		$this->levelFilters[] = $filter;
	}

	/**
	 * @return StringFilter[]
	 */
	public function getMessageFilters() {
		return $this->messageFilters;
	}

	/**
	 * @return StringFilter[]
	 */
	public function getLevelFilters() {
		return $this->levelFilters;
	}
}