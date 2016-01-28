<?php


use Page\LogPage;
use Step\Acceptance\Admin;

class NewRecordCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function test_simple_record_can_be_sent(ApiTester $api, Admin $I) {
		$message = 'some_message';
		$level = 'info';
		$context = [];
		$api->sendRecords('api', 'project1Token', [[
			'message' => $message,
			'level' => $level,
			'context' => $context,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(200);

		$I->loginAsAdmin();
		$page = new LogPage($I);
		$page->seeInRecords(1, $level, $message, $context);
    }

	public function test_record_with_difficult_context_can_be_sent(ApiTester $api, Admin $I) {
		$message = 'purchase';
		$level = 'emergency';
		$context = [
			"product" => [
				"name" => 'some_product',
				"price" => 55.2,
			],
			"user" => [
				"name" => "marek",
				"admin" => true,
			],
			"some_array" => ['value1', 'value2'],
			"null" => null
		];
		$api->sendRecords('api', 'project1Token', [[
			'message' => $message,
			'level' => $level,
			'context' => $context,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(200);

		$I->loginAsAdmin();
		$page = new LogPage($I);
		$page->seeInRecords(1, $level, $message, $context);
	}

	public function test_record_with_weird_characters_can_be_sent(ApiTester $api, Admin $I) {
		$message = 'ľ+ščľčžťžťýáííáéäúô\'"§↨7Wš\\/BĎ';
		$level = 'alert';
		$context = [
			'weird_value' => 'ľ+ščľčžťžťýáííáéäúô\'"§↨7Wš\\/BĎ'
		];
		$api->sendRecords('api', 'project1Token', [[
			'message' => $message,
			'level' => $level,
			'context' => $context,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(200);
	}

	public function test_more_records_can_be_sent(ApiTester $api, Admin $I) {
		$message1 = 'some_message';
		$level1 = 'info';
		$context1 = [];
		$message2 = 'another_message';
		$level2 = 'debug';
		$context2 = ['value' => 5];
		$api->sendRecords('api', 'project1Token', [[
			'message' => $message1,
			'level' => $level1,
			'context' => $context1,
			'time' => time()
		],[
			'message' => $message2,
			'level' => $level2,
			'context' => $context2,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(200);

		$page = new LogPage($I);
		$I->loginAsAdmin();
		$page->seeInRecords(1, $level1, $message1, $context1);
		$page->seeInRecords(1, $level2, $message2, $context2);
	}

	public function test_invalid_project_token_returns_400_code(ApiTester $api, Admin $I) {
		$message = 'some_message';
		$level = 'info';
		$context = [];
		$api->sendRecords('api', 'invalid_token', [[
			'message' => $message,
			'level' => $level,
			'context' => $context,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(400);
		$api->see('invalid project token');
	}

	public function test_invalid_level_does_not_save_record(ApiTester $api, Admin $I) {
		$message1 = 'some_message';
		$level1 = 'invalid_level';
		$context1 = [];
		$message2 = 'another_message';
		$level2 = 'debug';
		$context2 = ['value' => 5];
		$api->sendRecords('api', 'project1Token', [[
			'message' => $message1,
			'level' => $level1,
			'context' => $context1,
			'time' => time()
		],[
			'message' => $message2,
			'level' => $level2,
			'context' => $context2,
			'time' => time()
		]]);
		$api->seeResponseCodeIs(200);

		$page = new LogPage($I);
		$I->loginAsAdmin();
		$page->dontSeeInRecords(1, $level1, $message1, $context1);
		$page->seeInRecords(1, $level2, $message2, $context2);
	}
}
