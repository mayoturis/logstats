<?php

use Logstats\App\Installation\StepCollection;

class StepCollectionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
	public function createStepCollection() {
		$steps = [
			1 => [
				'short' => 'welcome',
				'menu' => 'Welcome',
			],
			/*2 => [
				'short' => 'systemCheck',
				'menu' => 'System check',
			],*/
			2 => [
				'short' => 'databaseSetup',
				'menu' => 'Database setup',
			],
			3 => [
				'short' => 'createTables',
				'menu' => 'Create tables',
			],
			4 => [
				'short' => 'generalSetup',
				'menu' => 'General Setup',
			],
			5 => [
				'short' => 'congratulations',
				'menu' => 'Congratulations',
			],


		];
		return new StepCollection($steps);
	}

	public function test_it_can_tell_if_step_exists() {
		$stepCollection = $this->createStepCollection();

		$this->assertTrue($stepCollection->stepExists(3));
		$this->assertNotTrue($stepCollection->stepExists(9));
	}

    public function test_it_can_get_step()
    {
		$stepCollection = $this->createStepCollection();
		$step = ['short' => 'congratulations', 'menu' => 'Congratulations'];

		$this->assertEquals($step, $stepCollection->getStep(5));

		try {
			$stepCollection->getStep(9);
			$this->fail('Exception should be thrown');
		} catch(\InvalidArgumentException $ex) {
			$this->assertTrue(true);
		}
    }

	public function test_it_can_get_key_by_short() {
		$stepCollection = $this->createStepCollection();

		$this->assertEquals(5, $stepCollection->getKeyByShort('congratulations'));
	}

	public function test_get_key_by_short_throws_exception_if_short_not_exists() {
		$stepCollection = $this->createStepCollection();

		try {
			$stepCollection->getKeyByShort('not_existing');
			$this->fail('Exception should be thrown');
		} catch(\InvalidArgumentException $ex) {
			$this->assertTrue(true);
		}
	}

	public function test_it_returns_next_step_for_short() {
		$stepCollection = $this->createStepCollection();
		$step = ['short' => 'congratulations', 'menu' => 'Congratulations'];

		$this->assertEquals($step, $stepCollection->nextStepForShort('generalSetup'));
	}

	public function test_nextStepForShort_returns_null_if_short_is_last() {
		$stepCollection = $this->createStepCollection();

		$this->assertNull($stepCollection->nextStepForShort('congratulations'));
	}

	public function test_nextKeyForShort_returns_next_key() {
		$stepCollection = $this->createStepCollection();

		$this->assertEquals(5, $stepCollection->nextKeyForShort('generalSetup'));
	}

	public function test_nextKeyForShort_returns_null_if_short_is_last() {
		$stepCollection = $this->createStepCollection();

		$this->assertNull($stepCollection->nextKeyForShort('congratulations'));
	}

	public function test_getStepByShort_returns_step() {
		$stepCollection = $this->createStepCollection();
		$step = ['short' => 'congratulations', 'menu' => 'Congratulations'];

		$this->assertEquals($step, $stepCollection->getStepByShort('congratulations'));
	}

	/**
	 * @param $stepCollection
	 */
	public function test_getStepByShort_throws_exception_if_short_not_exists() {
		$stepCollection = $this->createStepCollection();
		try {
			$stepCollection->getStepByShort('not_existing');
			$this->fail('Exception should by thrown');
		}
		catch (\InvalidArgumentException $ex) {
			$this->assertTrue(true);
		}
	}
}
