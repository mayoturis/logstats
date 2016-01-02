<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_TYPE', isset($_ENV['DB_TYPE']) ? $_ENV['DB_TYPE'] : null),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE_PATH', isset($_ENV['DB_DATABASE_PATH']) ? $_ENV['DB_DATABASE_PATH'] : null),
            'prefix'   => env('DB_PREFIX', isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : null),
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : null),
            'database'  => env('DB_DATABASE', isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : null),
            'username'  => env('DB_USERNAME', isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : null),
            'password'  => env('DB_PASSWORD', isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : null),
            'charset'   => env('DB_CHARSET', isset($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : null),
            'collation' => env('COLLATION', isset($_ENV['DB_COLLATION']) ? $_ENV['DB_COLLATION'] : null),
            'prefix'    => env('DB_PREFIX', isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : null),
            'strict'    => false,
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : null),
            'database' => env('DB_DATABASE', isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : null),
            'username' => env('DB_USERNAME', isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : null),
            'password' => env('DB_PASSWORD', isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : null),
            'charset'  => env('DB_CHARSET', isset($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : null),
            'prefix'   => env('DB_PREFIX', isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : null),
            'schema'   => env('DB_SCHEMA', isset($_ENV['DB_DATABASE_PATH']) ? $_ENV['DB_DATABASE_PATH'] : null),
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : null),
            'database' => env('DB_DATABASE', isset($_ENV['DB_DATABASE']) ? $_ENV['DB_DATABASE'] : null),
            'username' => env('DB_USERNAME', isset($_ENV['DB_USERNAME']) ? $_ENV['DB_USERNAME'] : null),
            'password' => env('DB_PASSWORD', isset($_ENV['DB_PASSWORD']) ? $_ENV['DB_PASSWORD'] : null),
            'charset'  => env('DB_CHARSET', isset($_ENV['DB_CHARSET']) ? $_ENV['DB_CHARSET'] : null),
            'prefix'   => env('DB_PREFIX', isset($_ENV['DB_PREFIX']) ? $_ENV['DB_PREFIX'] : null),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];
