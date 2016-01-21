<?php

use Logstats\Domain\Alerting\Email\LevelEmailAlerting;
use Logstats\Infrastructure\Repositories\Database\DbLevelEmailAlertingRepository;
use Logstats\Infrastructure\Repositories\Database\Factories\StdLevelEmailAlertingFactory;

class DbLevelEmailAlertingRepositoryTest extends DatabaseTestCase {

	/**
	 * @var DbLevelEmailAlertingRepository
	 */
	private $repository;

	public function setUp() {
		parent::setUp();

		$this->repository = new DbLevelEmailAlertingRepository(new StdLevelEmailAlertingFactory());
	}

	public function test_levelEmailAlerting_can_be_found_by_id() {
		$alerting = $this->repository->findById(1);

		$this->assertEquals(1, $alerting->getId());
		$this->assertEquals('email1', $alerting->getEmail());
		$this->assertEquals('info', $alerting->getLevel());
		$this->assertEquals(1, $alerting->getProjectId());
	}

	public function test_null_is_returned_if_levelEmailAlerting_doesnt_exists() {
		$this->assertNull($this->repository->findById(555));
	}

	public function test_alerting_can_be_inserted() {
		$alerting = new LevelEmailAlerting(1, 'info', 'some_email');

		$this->repository->insert($alerting);
		$savedAlerting = $this->repository->findById($alerting->getId());

		$this->assertNotEmpty($alerting->getId());
		$this->assertEquals('info', $savedAlerting->getLevel());
		$this->assertEquals('some_email', $savedAlerting->getEmail());
		$this->assertEquals(4, count($this->repository->findBy([])));
	}

	public function test_all_alertings_for_project_can_be_get() {
		$alertings = $this->repository->getAllForProject(1);

		$this->assertEquals(2, count($alertings));
		$this->assertTrue($alertings[0] instanceof LevelEmailAlerting);
		$this->assertTrue($alertings[1] instanceof LevelEmailAlerting);
	}

	public function test_alerting_can_be_deleted() {
		$alerting = $this->repository->findById(1);

		$this->repository->delete($alerting);

		$this->assertEquals(2, count($this->repository->findBy([])));
		$this->assertNull($this->repository->findById(1));
	}

	public function test_alertings_for_project_can_be_deleted() {
		$this->repository->deleteForProject(1);

		$this->assertEquals(1, count($this->repository->findBy([])));
		$this->assertNull($this->repository->findById(1));
		$this->assertNull($this->repository->findById(2));
	}

}