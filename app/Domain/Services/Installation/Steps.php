<?php  namespace Logstats\Domain\Services\Installation;

abstract class Steps {
	const WELCOME = "welcome";
	const DATABASE_SETUP = "databaseSetup";
	const CREATE_TABLES = "createTables";
	const GENERAL_SETUP = "generalSetup";
	const CONGRATULATIONS = "congratulations";
	const COMPLETE = "complete";
}