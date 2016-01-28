<?php  namespace Logstats\Domain\Record\Query;

class Where {
	private $propertyName;
	private $propertyValue;
	private $comparisonType;

	/**
	 * @return string
	 */
	public function getPropertyName() {
		return $this->propertyName;
	}

	/**
	 * @param string $propertyName
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
	 * @return string
	 */
	public function getComparisonType() {
		return $this->comparisonType;
	}

	/**
	 * @param string $comparisonType
	 */
	public function setComparisonType($comparisonType) {
		$this->comparisonType = $comparisonType;
	}
}