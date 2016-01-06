<?php  namespace Logstats\Infrastructure\Repositories\Database;

use Carbon\Carbon;
use Logstats\Domain\Record\Record;
use Logstats\Domain\Record\PropertyType;
use Logstats\Support\Date\CarbonConvertorInterface;

class DbRecordSaver {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	private $carbonConvertor;

	public function __construct(CarbonConvertorInterface $carbonConvertor) {
		$this->carbonConvertor = $carbonConvertor;
	}

	/**
	 * @param Record $record
	 * @return void
	 */
	public function newRecord(Record $record) {
		$messageId = $this->saveMessageAndGetId($record->getMessage(), $record->getProjectId());
		$this->insertRecord($record, $messageId);

		$properties = $this->tranformConextIntoSavableProperties($record->getContext());
		$existingPropertyTypes = $this->getExistingPropertyTypes(array_keys($properties), $messageId);
		$allPropertyTypes = $this->saveAndGetNonexistingPropertyTypes($properties, $existingPropertyTypes, $messageId);
		$this->saveProperties($properties, $allPropertyTypes, $record->getId());
	}

	private function saveMessageAndGetId($message, $projectId) {
		$messageIdRaw = \DB::table($this->messageTable)->where('message', $message)
			->where('project_id', $projectId)->first(['id']);
		if (!empty($messageIdRaw)) {
			return $messageIdRaw->id;
		}

		$id = \DB::table($this->messageTable)->insertGetId([
			"message" => $message,
			"project_id" => $projectId,
		]);

		return $id;
	}

	private function insertRecord(Record $record, $messageId) {
		$gmtDate = $this->carbonConvertor->carbonInGMT($record->getDate());
		$id = \DB::table($this->recordTable)->insertGetId([
			'date' => $gmtDate,
			'minute' => $gmtDate->minute,
			'hour' => $gmtDate->hour,
			'day' => $gmtDate->day,
			'month' => $gmtDate->month,
			'year' => $gmtDate->year,
			'project_id' => $record->getProjectId(),
			'message_id' => $messageId,
			'level' => $record->getLevel()
		]);
		$record->setId($id);
	}

	private function tranformConextIntoSavableProperties($context) {
		return array_dot($context);
	}

	private function getExistingPropertyTypes($propertyNames, $messageId) {
		$raw = \DB::table($this->propertyTypesTable)->where('message_id', $messageId)
			->whereIn('property_name', $propertyNames)->get(['property_name', 'type']);
		return $this->rawPropertyTypesToArray($raw);
	}

	private function rawPropertyTypesToArray(array $propertyTypes) {
		$propertyTypesArray = [];
		foreach ($propertyTypes as $propertyType) {
			$propertyTypesArray[$propertyType->property_name] = $propertyType->type;
		}
		return $propertyTypesArray;
	}

	private function saveAndGetNonexistingPropertyTypes($properties, $propertyTypes, $messageId) {
		$newPropertyTypes = [];
		foreach ($properties as $property => $value) {
			if (empty($propertyTypes[$property])) {
				$type = $this->determineNewPropertyType($value);
				$newPropertyTypes[$property] = $type;
				$propertyTypes[$property] = $type;
			}
		}
		$this->saveNewPropertyTypes($newPropertyTypes, $messageId);
		return $propertyTypes;
	}

	private function determineNewPropertyType($property) {
		if (is_null($property)) {
			return null;
		}

		if ($this->isNumber($property)) {
			return PropertyType::NUMBER;
		}

		if (is_bool($property)) {
			return PropertyType::BOOLEAN;
		}

		return PropertyType::STRING;
	}

	private function isNumber($property) {
		return is_float($property) || is_int($property);
	}

	private function saveNewPropertyTypes($propertyTypes, $messageId) {
		$insertValues = $this->createPropertyTypeInsertValues($propertyTypes, $messageId);
		\DB::table($this->propertyTypesTable)->where('message_id', $messageId)->whereIn('property_name', array_keys($propertyTypes))->delete();
		\DB::table($this->propertyTypesTable)->insert($insertValues);
	}

	private function createPropertyTypeInsertValues($propertyTypes, $messageId) {
		$insertValues = [];
		foreach ($propertyTypes as $propertyName => $type) {
			$insertValues[] = [
				'message_id' => $messageId,
				'type' => $type,
				'property_name' => $propertyName
			];
		}
		return $insertValues;
	}

	private function saveProperties($properties, $propertyTypes, $recordId) {
		$insertValues = $this->createPropertiesInsertValues($properties, $propertyTypes, $recordId);
		\DB::table($this->propertiesTable)->insert($insertValues);
	}

	private function createPropertiesInsertValues($properties, $propertyTypes, $recordId) {
		$insertValues = [];
		foreach ($properties as $name => $value) {
			$insertValues[] = $this->createPropertiesOneInsertValue($name, $value, $propertyTypes[$name], $recordId);
		}
		return $insertValues;
	}

	private function createPropertiesOneInsertValue($name, $value, $type, $recordId) {
		$insertValue = ["name" => $name, "record_id" => $recordId];
		$insertValue['value_number'] = null;
		$insertValue['value_string'] = null;
		$insertValue['value_boolean'] = null;

		if ($type == PropertyType::NUMBER) {
			if (is_numeric($value)) {
				$insertValue['value_number'] = (float) $value;
			} else {
				$insertValue['value_string'] = (string) $value;
			}
		} elseif ($type == PropertyType::STRING) {
			$insertValue['value_string'] = (string) $value;
		} elseif ($type == PropertyType::BOOLEAN) {
			$insertValue['value_boolean'] = (int) $value;
		}
		return $insertValue;
	}

}