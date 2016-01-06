<?php  namespace Logstats\Domain\ValueObjects;

class Pagination {

	private $page;
	private $pageCount;

	public function __construct($page, $pageCount) {
		$this->page = $page;
		$this->pageCount = $pageCount;
	}

	/**
	 * @return mixed
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return mixed
	 */
	public function getPageCount() {
		return $this->pageCount;
	}
}