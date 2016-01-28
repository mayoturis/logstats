<?php  namespace Logstats\Domain\ValueObjects;

class Pagination {

	private $page;
	private $pageCount;

	/**
	 * @param int $page
	 * @param int $pageCount
	 */
	public function __construct($page, $pageCount) {
		$this->page = $page;
		$this->pageCount = $pageCount;
	}

	/**
	 * @return int
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return int
	 */
	public function getPageCount() {
		return $this->pageCount;
	}
}