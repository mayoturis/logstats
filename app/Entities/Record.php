<?php  namespace Logstats\Entities; 

use Carbon\Carbon;

class Record {

	/**
	 * @var int
	 */
	private $id;
	/**
	 * @var string
	 */
	private $message;
	/**
	 * @var Carbon
	 */
	private $date;
	/**
	 * @var string
	 */
	private $level;
	/**
	 * @var int
	 */
	private $projectId;
	/**
	 * @var array
	 */
	private $properties;

	/**
	 * @param string $message
	 * @param Carbon $date
	 * @param string $level
	 * @param int $projectId
	 */
	public function __construct($message, Carbon $date, $level, $projectId) {
		$this->message = $message;
		$this->date = $date;
		$this->level = $level;
		$this->projectId = $projectId;
	}

	public function setProperties($properties) {
		$this->properties = $properties
	}
}