<?php  namespace Logstats\Services\Validators; 

use Logstats\Domain\Filters\ComparisonTypes;
use Logstats\Domain\Query\AggregateFunctions;
use Logstats\Domain\Query\Intervals;

class QueryValidator extends AbstractValidator {

	private $arrayErrors = [];

	public function isValidQuery($data) {
		$this->validateEvent($data);
		$this->validateInterval($data);
		$this->validateAggregate($data);
		$this->validateTargetProperty($data);
		$this->validateFilters($data);

		return empty($this->arrayErrors);
	}

	private function validateEvent($data) {
		if(empty($data['event'])) {
			$this->addError('Event is not set');
		}
	}

	private function validateInterval($data) {
		if(!empty($data['interval'])) {
			if (!in_array($data['interval'], Intervals::getAll())) {
				$this->addError('Invalid interval ' . $data['interval']);
			}
		}
	}

	private function validateAggregate($data) {
		if(empty($data['aggregate'])) {
			$this->addError('Analysis type is not set');
			return;
		}
		if (!in_array($data['aggregate'], AggregateFunctions::getAll())) {
			$this->addError('Invalid aggregate type ' . $data['aggregate']);
		}
	}

	private function validateTargetProperty($data) {
		if(!empty($data['aggregate'])) {
			if ($data['aggregate'] != AggregateFunctions::COUNT && empty($data['targetProperty'])) {
				$this->addError('Target property has to be set when using ' . $data['aggregate'] . ' analysis type');
			}
		}
	}

	private function validateFilters($data) {
		if (!empty($data['filters']) && is_array($data['filters'])) {
			foreach ($data['filters'] as $filter) {
				$this->validateFilter($filter);
			}
		}
	}

	private function validateFilter($filter) {
		if(empty($filter['propertyName'])) {
			$this->addError('When using filter, property name has to be set');
		}
		if(empty($filter['propertyValue'])) {
			$this->addError('When using filter, property value has to be set');
		}
		if(empty($filter['comparisonType'])) {
			$this->addError('When using filter comparison type has to be set');
			return;
		}
		if(!in_array($filter['comparisonType'], ComparisonTypes::getAll())) {
			$this->addError('Invalid comparison type '. $filter['comparisonType']);
		}
	}

	private function addError($error) {
		$this->arrayErrors[] = $error;
	}

	public function getArrayErrors() {
		return $this->arrayErrors;
	}
}