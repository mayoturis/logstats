<?php namespace Logstats\App\Installation;


class StepCollection {
	private $steps;

	public function __construct($steps) {
		$this->steps = $steps;
	}

	/**
	 * Gets all steps
	 *
	 * @return array
	 */
	public function getSteps() {
		return $this->steps;
	}

	/**
	 * Determines whether step exists
	 *
	 * @param int $key
	 * @return bool
	 */
	public function stepExists($key) {
		return array_key_exists($key, $this->steps);
	}

	/**
	 * Gets step by key
	 *
	 * @param $key
	 * @throws \InvalidArgumentException if step does not exist
	 * @return array
	 */
	public function getStep($key) {
		if (!$this->stepExists($key)) {
			throw new \InvalidArgumentException('Step does not exist');
		}

		return $this->steps[$key];
	}

	/**
	 * Gets next step for given step represented by its short name
	 *
	 * @param string $short
	 * @return array|null
	 */
	public function nextStepForShort($short) {
		$key = $this->getKeyByShort($short);
		if (array_key_exists($key + 1, $this->steps)) {
			return $this->steps[$key+1];
		} else {
			return null;
		}
	}

	/**
	 * Gets key of the next step
	 *
	 * @param string $short
	 * @return int|null
	 */
	public function nextKeyForShort($short) {
		$key = $this->getKeyByShort($short);
		if (array_key_exists($key + 1, $this->steps)) {
			return $key+1;
		} else {
			return null;
		}
	}

	/**
	 * Gets step by its given short name
	 *
	 * @param string $short
	 * @throws \InvalidArgumentException if step does not exist
	 * @return array
	 */
	public function getStepByShort($short) {
		foreach ($this->steps as $step) {
			if ($step['short'] == $short) {
				return $step;
			}
		}

		throw new \InvalidArgumentException('Step for given short does not exists: ' . $short);
	}

	/**
	 * Gets key of the step determined by its short name
	 *
	 * @param string $short
	 * @throws \InvalidArgumentException if step does not exist
	 * @return int
	 */
	public function getKeyByShort($short) {
		foreach ($this->steps as $key => $step) {
			if ($step['short'] == $short) {
				return $key;
			}
		}

		throw new \InvalidArgumentException('Step for given short does not exists: ' . $short);
	}


}