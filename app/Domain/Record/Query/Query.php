<?php  namespace Logstats\Domain\Record\Query;

use Carbon\Carbon;

class Query {
	private $event;
	private $groupBy;
	private $aggregation;
	private $aggregationTarget;
	private $interval;
	private $wheres = [];
	/**
	 * @var Carbon
	 */
	private $from;
	/**
	 * @var Carbon
	 */
	private $to;

	public function addWhere(Where $where) {
		$this->wheres[] = $where;
	}

	/**
	 * @return mixed
	 */
	public function getGroupBy() {
		return $this->groupBy;
	}

	/**
	 * @param mixed $groupBy
	 */
	public function setGroupBy($groupBy) {
		$this->groupBy = $groupBy;
	}

	/**
	 * @return mixed
	 */
	public function getAggregation() {
		return $this->aggregation;
	}

	/**
	 * @param mixed $aggregation
	 */
	public function setAggregation($aggregation) {
		$this->aggregation = $aggregation;
	}

	/**
	 * @return mixed
	 */
	public function getAggregationTarget() {
		return $this->aggregationTarget;
	}

	/**
	 * @param mixed $aggregationTarget
	 */
	public function setAggregationTarget($aggregationTarget) {
		$this->aggregationTarget = $aggregationTarget;
	}

	/**
	 * @return mixed
	 */
	public function getInterval() {
		return $this->interval;
	}

	/**
	 * @param mixed $interval
	 */
	public function setInterval($interval) {
		$this->interval = $interval;
	}

	/**
	 * @return mixed
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param mixed $event
	 */
	public function setEvent($event) {
		$this->event = $event;
	}

	/**
	 * @return Where[]
	 */
	public function getWheres() {
		return $this->wheres;
	}


	/**
	 * @return mixed
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @param mixed $from
	 */
	public function setFrom(Carbon $from) {
		$this->from = $from;
	}

	/**
	 * @return mixed
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @param mixed $to
	 */
	public function setTo(Carbon $to) {
		$this->to = $to;
	}

	public function isSetFrom() {
		return $this->from !== null;
	}

	public function isSetTo() {
		return $this->to !== null;
	}

	public function isSetGroupBy() {
		return $this->groupBy !== null;
	}

	public function isSetInterval() {
		return $this->interval !== null;
	}

	public function getTimeFrame() {
		$timeFrame = [];
		if ($this->isSetFrom()) {
			$timeFrame['from'] = $this->from->timestamp;
		}
		if ($this->isSetTo()) {
			$timeFrame['to'] = $this->to->timestamp;
		} else {
			$timeFrame['to'] = Carbon::now()->timestamp;
		}
		return $timeFrame;
	}
}