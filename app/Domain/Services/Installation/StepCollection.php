<?php namespace Logstats\Domain\Services\Installation;


class StepCollection {
	private $steps;

	public function __construct($steps) {
		$this->steps = $steps;
	}

	public function getSteps() {
		return $this->steps;
	}

	public function stepExists($key) {
		return array_key_exists($key, $this->steps);
	}

	public function getStep($key) {
		if (!$this->stepExists($key)) {
			throw new \InvalidArgumentException('Step does not exist');
		}

		return $this->steps[$key];
	}

	public function nextStepForShort($short) {
		$key = $this->getKeyByShort($short);
		if (array_key_exists($key + 1, $this->steps)) {
			return $this->steps[$key+1];
		} else {
			return null;
		}
	}

	public function nextKeyForShort($short) {
		$key = $this->getKeyByShort($short);
		if (array_key_exists($key + 1, $this->steps)) {
			return $key+1;
		} else {
			return null;
		}
	}

	/**
	 * @param $short
	 */
	public function getStepByShort($short) {
		foreach ($this->steps as $step) {
			if ($step['short'] == $short) {
				return $step;
			}
		}

		throw new \InvalidArgumentException('Step for given short does not exists: ' . $short);
	}

	public function getKeyByShort($short) {
		foreach ($this->steps as $key => $step) {
			if ($step['short'] == $short) {
				return $key;
			}
		}

		throw new \InvalidArgumentException('Step for given short does not exists: ' . $short);
	}


}