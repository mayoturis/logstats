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

	/**
	 * @param Where $where
	 */
	public function addWhere(Where $where) {
		$this->wheres[] = $where;
	}

	/**
	 * @return string
	 */
	public function getGroupBy() {
		return $this->groupBy;
	}

	/**
	 * @param string $groupBy
	 */
	public function setGroupBy($groupBy) {
		$this->groupBy = $groupBy;
	}

	/**
	 * @return AggregateFunctions
	 */
	public function getAggregation() {
		return $this->aggregation;
	}

	/**
	 * @param AggregateFunctions $aggregation
	 */
	public function setAggregation($aggregation) {
		$this->aggregation = $aggregation;
	}

	/**
	 * @return string
	 */
	public function getAggregationTarget() {
		return $this->aggregationTarget;
	}

	/**
	 * @param string $aggregationTarget
	 */
	public function setAggregationTarget($aggregationTarget) {
		$this->aggregationTarget = $aggregationTarget;
	}

	/**
	 * @return Intervals
	 */
	public function getInterval() {
		return $this->interval;
	}

	/**
	 * @param Intervals $interval
	 */
	public function setInterval($interval) {
		$this->interval = $interval;
	}

	/**
	 * @return string
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * @param string $event
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
	 * @return Carbon
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @param Carbon $from
	 */
	public function setFrom(Carbon $from) {
		$this->from = $from;
	}

	/**
	 * @return Carbon
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * @param Carbon $to
	 */
	public function setTo(Carbon $to) {
		$this->to = $to;
	}

	/**
	 * @return bool
	 */
	public function isSetFrom() {
		return $this->from !== null;
	}

	/**
	 * @return bool
	 */
	public function isSetTo() {
		return $this->to !== null;
	}

	/**
	 * @return bool
	 */
	public function isSetGroupBy() {
		return $this->groupBy !== null;
	}

	/**
	 * @return bool
	 */
	public function isSetInterval() {
		return $this->interval !== null;
	}

	/**
	 * @return array
	 */
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