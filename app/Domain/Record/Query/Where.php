<?php  namespace Logstats\Domain\Record\Query;

class Where {
	private $propertyName;
	private $propertyValue;
	private $comparisonType;

	/**
	 * @return mixed
	 */
	public function getPropertyName() {
		return $this->propertyName;
	}

	/**
	 * @param mixed $propertyName
	 */
	public function setPropertyName($propertyName) {
		$this->propertyName = $propertyName;
	}

	/**
	 * @return mixed
	 */
	public function getPropertyValue() {
		return $this->propertyValue;
	}

	/**
	 * @param mixed $propertyValue
	 */
	public function setPropertyValue($propertyValue) {
		$this->propertyValue = $propertyValue;
	}

	/**
	 * @return mixed
	 */
	public function getComparisonType() {
		return $this->comparisonType;
	}

	/**
	 * @param mixed $comparisonType
	 */
	public function setComparisonType($comparisonType) {
		$this->comparisonType = $comparisonType;
	}
}