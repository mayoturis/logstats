<?php  namespace Logstats\Infrastructure\Repositories\Database;

use Illuminate\Support\Facades\DB;
use Logstats\Infrastructure\Repositories\Database\Exceptions\PropertyTypeNotFoundException;

class DbPropertyFinder {
	private $recordTable = 'records';
	private $messageTable = 'messages';
	private $propertiesTable = 'properties';
	private $propertyTypesTable = 'property_types';

	public function getPropertyNamesForMessageId($messageId) {
		$rows = DB::table($this->propertyTypesTable)->where('message_id', $messageId)->get(['property_name']);

		$propertyNames = [];
		foreach ($rows as $row) {
			$propertyNames[] = $row->property_name;
		}

		return $propertyNames;
	}

	public function getPropertyTypesForMessageId($messageId,$propertyNames) {
		$propertyTypes = [];
		$rows = DB::table($this->propertyTypesTable)
			->where('message_id',$messageId)
			->whereIn('property_name', $propertyNames)
			->get();
		foreach ($rows as $row) {
			$propertyTypes[$row->property_name] = $row->type;
		}

		foreach ($propertyNames as $name) {
			if (!array_key_exists($name, $propertyTypes)) {
				throw new PropertyTypeNotFoundException('Property ' . $name . ' was not found');
			}
		}

		return $propertyTypes;
	}
}