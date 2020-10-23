<?php
include 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->load();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/config/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/config/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog_migrations',
        'default_environment' => 'development',
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
            'host' => getenv('DB_HOST') ?: 'localhost',
            'name' => getenv('DB_NAME') ?: 'development_db',
            'user' => getenv('DB_USER') ?: 'root',
            'pass' => getenv('DB_PASS') ?: '',
            'port' => getenv('DB_PORT') ?: '3306',
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => strtolower(getenv('DB_ADAPTER')) ?: 'mysql',
            'host' => getenv('DB_HOST') ?: 'localhost',
            'name' => getenv('DB_NAME') ?: 'testing_db',
            'user' => getenv('DB_USER') ?: 'root',
            'pass' => getenv('DB_PASS') ?: '',
            'port' => getenv('DB_PORT') ?: '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
