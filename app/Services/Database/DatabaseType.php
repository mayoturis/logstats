<?php  namespace Logstats\Services\Database; 

abstract class DatabaseType {
	const MYSQL = "mysql";
	const POSTGRESQL = "pgsql";
	const MSSQL = "mssql";
	const SQLITE = "sqlite";
}