<?php
include 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


var_dump(getenv());


return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/resources/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/resources/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog_migrations',
        'default_environment' => 'production',
        
        'production' => [
            'adapter' => strtolower(getenv('DB_ADAPTER')) ?: 'mysql',
            'host' => getenv('DB_HOST') ?: 'localhost',
            'name' => getenv('DB_NAME') ?: 'production_db',
            'user' => getenv('DB_USER') ?: 'root',
            'pass' => getenv('DB_PASS') ?: '',
            'port' => getenv('DB_PORT') ?: '3306',
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => strtolower(getenv('DB_ADAPTER')) ?: 'mysql',
            'host' => getenv('DB_HOST_DEV') ?: 'localhost',
            'name' => getenv('DB_NAME_DEV') ?: 'development_db',
            'user' => getenv('DB_USER_DEV') ?: 'root',
            'pass' => getenv('DB_PASS_DEV') ?: '',
            'port' => getenv('DB_PORT_DEV') ?: '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => strtolower(getenv('DB_ADAPTER')) ?: 'mysql',
            'host' => getenv('DB_HOST_TESTING') ?: 'localhost',
            'name' => getenv('DB_NAME_TESTING') ?: 'testing_db',
            'user' => getenv('DB_USER_TESTING') ?: 'root',
            'pass' => getenv('DB_PASS_TESTING') ?: '',
            'port' => getenv('DB_PORT_TESTING') ?: '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
