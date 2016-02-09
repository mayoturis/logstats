<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectWithRecordsTimerangeSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$projectId = DB::table('projects')->insertGetId([
			'name' => 'Demo project',
			'token' => 'demotoken',
			'created_at' => Carbon::now()
		]);

		$from = time() - 7 * 86000;
		$to = time() + 365 * 86000;
		$shift = 7 * 86000;

		$this->createMessages($projectId, $from, $to, $shift);
		$this->createEvents($projectId, $from, $to, $shift);
	}



	private function createMessages($projectId, $from, $to, $shift) {
		$faker = \Faker\Factory::create();

		$messageIds = [];
		for ($i = 0; $i < 50; $i++) {
			$messageIds[] = DB::table('messages')->insertGetId([
				'message' => $faker->sentence(20),
				'project_id' => $projectId
			]);
		}

		$levels = ['debug', 'info', 'notice', 'alert', 'warning', 'emergency', 'critical', 'error'];

		while ($from < $to) {
			$currentTo = $from + $shift;
			$records = [];
			for ($i = 0; $i < 1000; $i++) {
				$time = Carbon::createFromTimestamp(rand($from,$currentTo));
				$records[] = [
					'date' => $time,
					'minute' => $time->minute,
					'hour' => $time->hour,
					'day' => $time->day,
					'month' => $time->month,
					'year' => $time->year,
					'project_id' => $projectId,
					'message_id' => $this->rand_value($messageIds),
					'level' => $this->rand_value($levels),
				];
			}

			DB::table('records')->insert($records);
			$from += $shift;
		}
	}

	private function createEvents($projectId, $from, $to, $shift) {
		$messageId = DB::table('messages')->insertGetId([
			'message' => "purchase",
			'project_id' => $projectId
		]);


		while ($from < $to) {
			$currentTo = $from + $shift;
			$recordIds = [];
			for ($i = 0; $i < 1000; $i++) {
				$time = Carbon::createFromTimestamp(rand($from,$currentTo));
				$recordIds[] = DB::table('records')->insertGetId([
					'date' => $time,
					'minute' => $time->minute,
					'hour' => $time->hour,
					'day' => $time->day,
					'month' => $time->month,
					'year' => $time->year,
					'project_id' => $projectId,
					'message_id' => $messageId,
					'level' => 'info',
				]);
			}

			DB::table('property_types')->insert([
				[
					"property_name" => "name",
					"type" => "string",
					"message_id" => $messageId,
				],
				[
					"property_name" => "product",
					"type" => "string",
					"message_id" => $messageId,
				],
				[
					"property_name" => "brand",
					"type" => "string",
					"message_id" => $messageId,
				],
				[
					"property_name" => "age",
					"type" => "number",
					"message_id" => $messageId,
				],
				[
					"property_name" => "price",
					"type" => "number",
					"message_id" => $messageId,
				],
				[
					"property_name" => "coupon_used",
					"type" => "boolean",
					"message_id" => $messageId,
				]
			]);
			$properties = [];
			foreach ($recordIds as $recordId) {
				$properties[] = [
					"name" => "name",
					"value_string" => $this->getRandomName(),
					"value_number" => null,
					"value_boolean" => null,
					"record_id" => $recordId,
				];
				$properties[] = [
					"name" => "product",
					"value_string" => $this->getRandomProduct(),
					"value_number" => null,
					"value_boolean" => null,
					"record_id" => $recordId,
				];
				$properties[] = [
					"name" => "brand",
					"value_string" => $this->getRandomBrand(),
					"value_number" => null,
					"value_boolean" => null,
					"record_id" => $recordId,
				];
				$properties[] = [
					"name" => "age",
					"value_string" => null,
					"value_number" => $this->getRandomAge(),
					"value_boolean" => null,
					"record_id" => $recordId,
				];
				$properties[] = [
					"name" => "price",
					"value_string" => null,
					"value_number" => $this->getRandomPrice(),
					"value_boolean" => null,
					"record_id" => $recordId,
				];
				$properties[] = [
					"name" => "coupon_used",
					"value_string" => null,
					"value_number" => null,
					"value_boolean" => $this->rand_value([0,1]),
					"record_id" => $recordId,
				];
			}

			DB::table('properties')->insert($properties);
			$from += $shift;
		}
	}

	private function rand_value(array $array) {
		$key = array_rand($array);
		return $array[$key];
	}

	private function getRandomName() {
		return $this->rand_value(['John', 'Tom', 'Jake', 'Charlie', 'Megan', 'Berta', 'Penny',
			'Ted','Alice', 'Marshall', 'Barney', 'Christie']);
	}

	private function getRandomProduct() {
		return $this->rand_value(['ball', 'shoes', 'sneakers', 'shorts', 'jersey', 't-shirt', 'pullover']);
	}

	private function getRandomBrand() {
		return $this->rand_value(['adidas', 'nike', 'puma']);
	}

	private function getRandomAge() {
		return rand(20,80);
	}

	private function getRandomPrice() {
		return rand(100,400);
	}
}