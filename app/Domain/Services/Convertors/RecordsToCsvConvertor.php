<?php  namespace Logstats\Domain\Services\Convertors; 

use Logstats\Domain\Record\Record;

class RecordsToCsvConvertor implements RecordsToCsvConvertorInterface {


	/**
	 * @param Record[] $records
	 * @return string
	 */
	public function convertRecordsToCsvString(array $records) {
		$csvLines = [];
		foreach ($records as $record) {
			$csvLines[] = $this->convertOneRecordToCsvString($record);
		}

		return join("\r\n", $csvLines);
	}

	private function convertOneRecordToCsvString(Record $record) {
		$csvParts = $this->getCsvParts($record);
		$escapedParts = $this->escapeCsvParts($csvParts);
		return join(",", $escapedParts);
	}

	private function getCsvParts(Record $record) {
		$csvParts = [];
		$csvParts[] = (string) $record->getDate();
		$csvParts[] = $record->getLevel();
		$csvParts[] = $record->getMessage();
		$csvParts[] = $this->convertRecordContextToCsvString($record->getContext());

		return $csvParts;
	}

	private function convertRecordContextToCsvString(array $context) {
		return empty($context) ? "" : json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	private function escapeCsvParts(array $csvParts) {
		return array_map(function($csvPart) {
			return $this->escapeOneCsvPart($csvPart);
		}, $csvParts);
	}

	private function escapeOneCsvPart($csvPart) {
		return '"'. str_replace('"', '""', $csvPart) .'"';
	}
}