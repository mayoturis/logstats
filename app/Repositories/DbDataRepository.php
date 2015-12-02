<?php  namespace Logstats\Repositories; 

use Carbon\Carbon;
use Logstats\Entities\Record;
use Logstats\Repositories\Contracts\DataRepository;

class DbDataRepository implements DataRepository {

	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	/**
	 * @param Record $record
	 * @return void
	 */
	public function newRecord(Record $record) {
		$messageId = $this->saveMessageAndGetId($record->getMessage(), $record->getProjectId());
		$this->saveRecord($record, $messageId);
		$properties = array_dot($record->getContext());

		$existingPropertyTypes = $this->getExistingPropertyTypes(array_keys($properties), $messageId);
		$allPropertyTypes = $this->saveAndGetNonexistingPropertyTypes($properties, $existingPropertyTypes, $messageId);
		$this->saveProperties($properties, $allPropertyTypes, $record->getId());
	}

	private function saveMessageAndGetId($message, $projectId) {
		$messageInDb = \DB::table($this->messageTable)->where('message', $message)
							->where('project_id', $projectId)->first();
		if (!empty($messageInDb)) {
			return $messageInDb->id;
		}

		$id = \DB::table($this->messageTable)->insertGetId([
			"message" => $message,
			"project_id" => $projectId,
		]);

		return $id;
	}

	private function saveRecord(Record $record, $messageId) {
		$gmtDate = $this->dateInGMT($record->getDate());
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

	/**
	 * @param Carbon $date
	 * @return Carbon
	 */
	private function dateInGMT(Carbon $date) {
		$new = Carbon::createFromFormat('Y-m-d H:i:s', $date);
		$new->setTimezone('GMT');
		return $new;
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

		if ($type == PropertyType::NUMBER) {
			if (is_numeric($value)) {
				$insertValue['value_number'] = (float) $value;
			} else {
				$insertValue['value_string'] = (string) $value;
			}
		} elseif ($type == PropertyType::STRING) {
			$insertValue['value_string'] = (string) $value;
		}
		return $insertValue;
	}

	private function saveAndGetNonexistingPropertyTypes($properties, $propertyTypes, $messageId) {
		$newPropertyTypes = [];
		foreach ($properties as $property => $value) {
			if (!array_key_exists($property, $propertyTypes)) {
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

		return PropertyType::STRING;
	}

	private function isNumber($property) {
		return is_float($property) || is_int($property);
	}

	private function saveNewPropertyTypes($propertyTypes, $messageId) {
		$insertValues = $this->createPropertyTypeInsertValues($propertyTypes, $messageId);
		\DB::table($this->propertyTypesTable)->insert($insertValues);
	}

	private function createPropertyTypeInsertValues($propertyTypes, $messageId) {
		$insertValues = [];
		foreach ($propertyTypes as $propertyName => $type) {
			if ($type !== null) {
				$insertValues[] = [
					'message_id' => $messageId,
					'type' => $type,
					'property_name' => $propertyName
				];
			}
		}
		return $insertValues;
	}
}