<?php  namespace Logstats\Repositories\Database\Factories; 

use Logstats\Entities\Record;
use Logstats\Services\Date\CarbonConvertor;
use Logstats\Services\Date\CarbonConvertorInterface;

class StdRecordFactory {

	private $arrayRecords;

	private $carbonConvertor;

	public function __construct(CarbonConvertor $carbonConvertor) {
		$this->carbonConvertor = $carbonConvertor;
	}

	public function makeFromMoreRaws($rawRecords) {
		$this->arrayRecords = [];
		foreach ($rawRecords as $rawRecord) {
			if (!isset($this->arrayRecords[$rawRecord->id])) {
				$this->addArrayRecord($rawRecord);
			}
			if($rawRecord->name !== null) {
				$this->addContextItem($rawRecord);
			}
		}

		return $this->getRecords();
	}

	private function addArrayRecord($rawRecord) {
		$this->arrayRecords[$rawRecord->id] = [
			'id' => $rawRecord->id,
			'date' => $rawRecord->date,
			'message' => $rawRecord->message,
			'level' => $rawRecord->level,
			'project_id' => $rawRecord->project_id,
			'context' => [],
		];
	}

	private function addContextItem($rawRecord) {
		$value = $this->getValue($rawRecord);
		assignToArrayByDot($this->arrayRecords[$rawRecord->id]['context'], $rawRecord->name, $value);
	}

	private function getValue($rawRecord) {
		if ($rawRecord->value_string !== null) {
			return $rawRecord->value_string;
		}
		if ($rawRecord->value_number !== null) {
			return (float) $rawRecord->value_number;
		}
		if ($rawRecord->value_boolean !== null) {
			return (bool) $rawRecord->value_boolean;
		}

		return null;
	}

	private function getRecords() {
		$records = [];
		foreach ($this->arrayRecords as $arrayRecord) {
			$record = new Record(
				$arrayRecord['level'],
				$arrayRecord['message'],
				$this->carbonConvertor->carbonFromStandartGMTString($arrayRecord['date']),
				$arrayRecord['project_id'],
				$arrayRecord['context']
			);
			$record->setId($arrayRecord['id']);

			$records[] = $record;
		}

		return $records;
	}


}