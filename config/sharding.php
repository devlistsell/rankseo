<?php

return [
    'server-jp-1' => [
        'driver' => 'mysql',
        'url' => '',
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'userdb',
        'username' => 'root',
        'password' => 'root',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => 'InnoDB',
        'timezone'  => '+00:00',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

    'server-jp-2' => [
        'driver' => 'mysql',
        'url' => '',
        'host' => '0.0.0.2',
        'port' => 3306,
        'database' => 'userdb',
        'username' => 'root',
        'password' => 'root',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => 'InnoDB',
        'timezone'  => '+00:00',
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ]
];
