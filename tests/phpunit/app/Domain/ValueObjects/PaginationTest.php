<?php
use Logstats\Domain\ValueObjects\Pagination;

class PaginationTest extends TestCase {
	public function test_pagination_can_be_constructed_and_getters_work() {
		$page = 'page';
		$pageCount = 5;
		$pagination = new Pagination($page, $pageCount);

		$this->assertEquals($page, $pagination->getPage());
		$this->assertEquals($pageCount, $pagination->getPageCount());
	}
}